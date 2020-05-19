<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\PlayerRepository;

/**
 * A defrag player, which can authenticate
 *
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 * @UniqueEntity("email", message="The email {{ is already used }}")
 * @UniqueEntity("username", message="The Username {{ value }} is already used")
 */
class Player implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Username can't be empty")
     * @Assert\NotNull(message="Username must be set")
     * @Assert\Length(
     *      min=2, minMessage="Username must be at least {{ limit }} charachters long",
     *      max=15, maxMessage="Username must be at mort {{ limit }} characters long"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Email must be set")
     * @Assert\NotBlank(message="Email can't be empty")
     * @Assert\Email(message="The address {{ value }} is not valid")
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotNull(message="Password must be set")
     * @Assert\NotBlank(message="Password can't be empty")
     * @Assert\Length(min=6, minMessage="Password must be at least 6 characters long")
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
        ];
    }
}
