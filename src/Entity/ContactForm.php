<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Constant\Locale;
use App\EventListener\Doctrine\ContactFormListener;
use App\Repository\ContactFormRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactFormRepository::class)]
#[ORM\EntityListeners([ContactFormListener::class])]
#[Gedmo\SoftDeleteable]
#[ApiResource(
    operations: [
        new Post(),
    ],
    normalizationContext: ['groups' => ['contact_form.get']],
    denormalizationContext: ['groups' => ['contact_form.post']],
)]
class ContactForm extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('contact_form.get')]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups('contact_form.post')]
    private ?string $fullName = null;

    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups('contact_form.post')]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups('contact_form.post')]
    private ?string $phoneNumber = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups('contact_form.post')]
    private ?string $subject = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Groups('contact_form.post')]
    private ?string $message = null;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [Locale::class, 'getAll'])]
    #[ORM\Column(length: 255)]
    #[Groups('contact_form.post')]
    private ?string $locale = null;

    #[ORM\Column(nullable: true)]
    #[ApiProperty(example: [
        [
            'title' => 'Title',
            'value' => 'Value',
        ]
    ])]
    #[Groups('contact_form.post')]
    private array $extensions = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function setExtensions(?array $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }
}
