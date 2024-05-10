<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
#[UniqueEntity('uniqueName')]
#[Gedmo\SoftDeleteable]
#[ApiResource(
    operations: [
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['setting.get']],
    paginationClientEnabled: false,
    paginationEnabled: false,
)]
class Setting extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups('setting.get')]
    private ?string $uniqueName = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    #[Groups('setting.get')]
    private array $extensions = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUniqueName(): ?string
    {
        return $this->uniqueName;
    }

    public function setUniqueName(string $uniqueName): self
    {
        $this->uniqueName = $uniqueName;

        return $this;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function setExtensions(array $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }
}
