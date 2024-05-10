<?php

declare(strict_types=1);

namespace App\Entity;

use App\Constant\Entity\UserConstant;
use App\EventListener\Doctrine\UserListener;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]
#[ORM\EntityListeners([UserListener::class])]
#[UniqueEntity('email')]
#[Gedmo\SoftDeleteable]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank(groups: ['User.post'])]
    #[Assert\Length(min: 7, max: 255)]
    protected ?string $plainPassword = null;

    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [UserConstant::ADMIN];


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        $this->updatedAt = new DateTime();

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->roles[0] ?? null;
    }

    public function setRole(string $role): self
    {
        $this->roles = [$role];
        $this->updatedAt = new DateTime();

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
