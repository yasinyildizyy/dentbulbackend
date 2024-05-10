<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\PositionTrait;
use App\Repository\SocialMediaAccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SocialMediaAccountRepository::class)]
#[Gedmo\SoftDeleteable]
#[ApiResource(
    operations: [
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['social_media_account.get']],
    filters: ['position.order_filter'],
    paginationClientEnabled: false,
    paginationEnabled: false,
)]
class SocialMediaAccount extends AbstractEntity
{
    use PositionTrait;
    use IsActiveTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups('social_media_account.get')]
    private ?string $username = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups('social_media_account.get')]
    private ?string $iconName = null;

    #[Assert\NotBlank]
    #[Assert\Url]
    #[Assert\Length(min: 0, max: 300)]
    #[ORM\Column(length: 300)]
    #[Groups('social_media_account.get')]
    private ?string $url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getIconName(): ?string
    {
        return $this->iconName;
    }

    public function setIconName(string $iconName): self
    {
        $this->iconName = $iconName;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
