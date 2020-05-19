<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PlayerController extends AbstractController
{
    /**
     * @Route("/register", methods={"POST"}, name="player_register")
     */
    public function index(Request $request, ValidatorInterface $validator, EntityManager $manager, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $data = json_decode($request->getContent());

        $player = (new Player)
            ->setEmail($data->email ?? null)
            ->setUsername($data->username ?? null)
            ->setPassword($data->password ?? null);
        
        $errors = [];
        foreach ($validator->validate($player) as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        if (!empty($errors)) {
            return $this->json($errors, 400);
        }

        try {
            $player->setPassword($encoder->encodePassword($player, $player->getPassword()));
            $manager->persist($player);
            $manager->flush();
        } catch (DBALException $exception) {
            return $this->json([
                'database error' => $exception->getMessage()
            ], 400);
        } catch (Exception $exception) {
            return $this->json([
                'server error' => $exception->getMessage()
            ], 500);
        }

        $player->eraseCredentials();

        return $this->json($player->toArray(), 201);
    }
}
