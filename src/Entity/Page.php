<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\PositionTrait;
use App\EventListener\Doctrine\PageListener;
use App\Repository\PageRepository;
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

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ORM\EntityListeners([PageListener::class])]
#[Gedmo\SoftDeleteable]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(),
    ],
    normalizationContext: ['groups' => ['page.get']],
    order: ['position' => 'ASC'],
)]
class Page extends AbstractEntity implements Translatable
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
    #[Groups('page.get')]
    private ?string $title = null;

    #[Assert\Length(min: 0, max: 300)]
    #[ORM\Column(length: 300)]
    #[Gedmo\Slug(fields: ['title'])]
    #[ApiProperty(identifier: true)]
    private ?string $slug = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Gedmo\Translatable]
    #[Groups('page.get')]
    private ?string $description = null;

    #[Assert\Length(min: 0, max: 300)]
    #[ORM\Column(length: 300, nullable: true)]
    #[Groups('page.get')]
    private ?string $videoId = null;

    #[Assert\NotNull(groups: ['Page.post'])]
    #[Assert\Image(maxSize: '1M')]
    #[Vich\UploadableField(mapping: 'page', fileNameProperty: 'photoPath')]
    private ?File $photoFile = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $photoPath = null;

    #[Groups('page.get')]
    public ?string $photoUrl = null;

    #[ORM\OneToMany(mappedBy: 'page', targetEntity: PageSub::class, cascade: ['all'], orphanRemoval: true)]
    #[Groups('page.get')]
    private Collection $subs;

    public function __construct()
    {
        $this->subs = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(?string $videoId): self
    {
        $this->videoId = $videoId;

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

    /**
     * @return Collection<int, PageSub>
     */
    public function getSubs(): Collection
    {
        return $this->subs;
    }

    public function addSub(PageSub $sub): self
    {
        if (!$this->subs->contains($sub)) {
            $this->subs->add($sub);
            $sub->setPage($this);
        }

        return $this;
    }

    public function removeSub(PageSub $sub): self
    {
        if ($this->subs->removeElement($sub)) {
            // set the owning side to null (unless already changed)
            if ($sub->getPage() === $this) {
                $sub->setPage(null);
            }
        }

        return $this;
    }
}
