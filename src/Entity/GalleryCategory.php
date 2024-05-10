<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\GalleryCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GalleryCategoryRepository::class)]
#[Gedmo\SoftDeleteable]
#[ApiResource(
    operations: [
        new Get(),
    ],
    normalizationContext: ['groups' => ['gallery.get']],
)]
class GalleryCategory extends AbstractEntity implements Translatable
{
    use GedmoLocaleTrait;
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
    #[Groups('gallery.get')]
    private ?string $name = null;

    #[Assert\Length(min: 0, max: 300)]
    #[ORM\Column(length: 300)]
    #[Gedmo\Slug(fields: ['name'])]
    #[ApiProperty(identifier: true)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Gallery::class, orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Groups('gallery.get')]
    private Collection $galleries;

    public function __construct()
    {
        $this->galleries = new ArrayCollection();
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Gallery>
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }
}
