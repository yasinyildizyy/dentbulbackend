<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\PositionTrait;
use App\EventListener\Doctrine\CureSubListener;
use App\Repository\CureSubRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CureSubRepository::class)]
#[ORM\EntityListeners([CureSubListener::class])]
#[Gedmo\SoftDeleteable]
#[Vich\Uploadable]
class CureSub extends AbstractEntity implements Translatable
{
    use GedmoLocaleTrait;
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    #[Groups('cure.get')]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Gedmo\Translatable]
    #[Groups('cure.get')]
    private ?string $description = null;

    #[Assert\Image(maxSize: '1M')]
    #[Vich\UploadableField(mapping: 'cure', fileNameProperty: 'photoPath')]
    private ?File $photoFile = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $photoPath = null;

    #[Groups('cure.get')]
    public ?string $photoUrl = null;

    #[ORM\ManyToOne(inversedBy: 'subs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cure $cure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function setPhotoFile(?File $photoFile): self
    {
        $this->photoFile = $photoFile;
        $this->updatedAt = new DateTime();

        return $this;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function setPhotoPath(?string $photoPath): self
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    public function getCure(): ?Cure
    {
        return $this->cure;
    }

    public function setCure(?Cure $cure): self
    {
        $this->cure = $cure;

        return $this;
    }
}
