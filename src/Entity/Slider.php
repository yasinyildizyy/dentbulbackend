<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\PositionTrait;
use App\EventListener\Doctrine\SliderListener;
use App\Repository\SliderRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SliderRepository::class)]
#[ORM\EntityListeners([SliderListener::class])]
#[Gedmo\SoftDeleteable]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new GetCollection(
            filters: ['position.order_filter']
        ),
        new Get(),
    ],
    normalizationContext: ['groups' => ['slider.get']],
    order: ['position' => 'ASC'],
    paginationClientEnabled: false,
    paginationEnabled: false,
)]
class Slider extends AbstractEntity implements Translatable
{
    use GedmoLocaleTrait;
    use PositionTrait;
    use IsActiveTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    #[Groups('slider.get')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Gedmo\Translatable]
    #[Groups('slider.get')]
    private ?string $description = null;

    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Translatable]
    #[Groups('slider.get')]
    private ?string $buttonName = null;

    #[Assert\Url]
    #[Assert\Length(min: 0, max: 300)]
    #[ORM\Column(length: 300, nullable: true)]
    #[Gedmo\Translatable]
    #[Groups('slider.get')]
    private ?string $buttonUrl = null;

    #[Assert\NotNull(groups: ['Slider.post'])]
    #[Assert\Image(maxSize: '1M')]
    #[Vich\UploadableField(mapping: 'slider', fileNameProperty: 'photoPath')]
    private ?File $photoFile = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $photoPath = null;

    #[Groups('slider.get')]
    public ?string $photoUrl = null;

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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getButtonName(): ?string
    {
        return $this->buttonName;
    }

    public function setButtonName(?string $buttonName): self
    {
        $this->buttonName = $buttonName;

        return $this;
    }

    public function getButtonUrl(): ?string
    {
        return $this->buttonUrl;
    }

    public function setButtonUrl(?string $buttonUrl): self
    {
        $this->buttonUrl = $buttonUrl;

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
