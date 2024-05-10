<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\PositionTrait;
use App\EventListener\Doctrine\DoctorListener;
use App\Repository\DoctorRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
#[ORM\EntityListeners([DoctorListener::class])]
#[Gedmo\SoftDeleteable]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new GetCollection(
            filters: ['position.order_filter'],
        ),
    ],
    normalizationContext: ['groups' => ['doctor.get']],
    order: ['position' => 'ASC'],
)]
class Doctor extends AbstractEntity implements Translatable
{
    use GedmoLocaleTrait;
    use IsActiveTrait;
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups('doctor.get')]
    private ?string $fullName = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    #[Groups('doctor.get')]
    private ?string $title = null;

    #[Assert\NotNull(groups: ['Doctor.post'])]
    #[Assert\Image(maxSize: '1M')]
    #[Vich\UploadableField(mapping: 'doctor', fileNameProperty: 'photoPath')]
    private ?File $photoFile = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $photoPath = null;

    #[Groups('doctor.get')]
    public ?string $photoUrl = null;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
}
