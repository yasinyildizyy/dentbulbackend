<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\PositionTrait;
use App\EventListener\Doctrine\CureListener;
use App\Repository\CureRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CureRepository::class)]
#[ORM\EntityListeners([CureListener::class])]
#[Gedmo\SoftDeleteable]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new GetCollection(
            filters: ['position.order_filter', 'is_show_homepage.boolean_filter'],
        ),
        new Get(
            normalizationContext: ['groups' => ['cure.get']],
        ),
    ],
    normalizationContext: ['groups' => ['cure.collection.get']],
    order: ['position' => 'ASC'],
)]
class Cure extends AbstractEntity implements Translatable
{
    use GedmoLocaleTrait;
    use PositionTrait;
    use IsActiveTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    #[Groups(['cure.collection.get', 'cure.get'])]
    private ?string $name = null;

    #[Assert\Length(min: 0, max: 300)]
    #[ORM\Column(length: 300)]
    #[Gedmo\Slug(fields: ['name'])]
    #[ApiProperty(identifier: true)]
    #[Groups(['cure.collection.get', 'cure.get'])]
    private ?string $slug = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Gedmo\Translatable]
    #[Groups('cure.collection.get')]
    private ?string $shortDescription = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Gedmo\Translatable]
    #[Groups('cure.get')]
    private ?string $longDescription = null;

    #[Assert\Length(min: 0, max: 300)]
    #[ORM\Column(length: 300, nullable: true)]
    #[Groups('cure.get')]
    private ?string $videoId = null;

    #[ORM\Column]
    private bool $isShowHomepage = false;

    #[Assert\NotNull(groups: ['Cure.post'])]
    #[Assert\Image(maxSize: '1M')]
    #[Vich\UploadableField(mapping: 'cure', fileNameProperty: 'photoPath')]
    private ?File $photoFile = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $photoPath = null;

    #[Groups(['cure.collection.get', 'cure.get'])]
    public ?string $photoUrl = null;

    #[ORM\ManyToOne(inversedBy: 'cures')]
    #[Groups('cure.get')]
    private ?FaqGroup $faqGroup = null;

    #[ORM\OneToMany(mappedBy: 'cure', targetEntity: CureSub::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Groups('cure.get')]
    private Collection $subs;

    public function __construct()
    {
        $this->subs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): self
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(?string $videoId): self
    {
        $this->videoId = $videoId;

        return $this;
    }

    public function isShowHomepage(): bool
    {
        return $this->isShowHomepage;
    }

    public function setIsShowHomepage(bool $isShowHomepage): self
    {
        $this->isShowHomepage = $isShowHomepage;

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

    public function getFaqGroup(): ?FaqGroup
    {
        return $this->faqGroup;
    }

    public function setFaqGroup(?FaqGroup $faqGroup): self
    {
        $this->faqGroup = $faqGroup;

        return $this;
    }

    /**
     * @return Collection<int, CureSub>
     */
    public function getSubs(): Collection
    {
        return $this->subs;
    }

    public function addSub(CureSub $sub): self
    {
        if (!$this->subs->contains($sub)) {
            $this->subs->add($sub);
            $sub->setCure($this);
        }

        return $this;
    }

    public function removeSub(CureSub $sub): self
    {
        if ($this->subs->removeElement($sub)) {
            // set the owning side to null (unless already changed)
            if ($sub->getCure() === $this) {
                $sub->setCure(null);
            }
        }

        return $this;
    }
}
