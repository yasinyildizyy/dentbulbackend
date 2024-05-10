<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Constant\Entity\MenuConstant;
use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\PositionTrait;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[Gedmo\SoftDeleteable]
#[ApiResource(
    operations: [
        new GetCollection(
            filters: ['menu.search_filter', 'position.order_filter', 'parent.exists_filter', 'parent.search_filter'],
        ),
    ],
    normalizationContext: ['groups' => ['menu.get']],
    order: ['position' => 'ASC'],
    paginationClientEnabled: false,
    paginationEnabled: false,
)]
class Menu extends AbstractEntity implements Translatable
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
    #[Groups('menu.get')]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 400)]
    #[ORM\Column(length: 400)]
    #[Gedmo\Translatable]
    #[Groups('menu.get')]
    private ?string $path = null;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [MenuConstant::class, 'getTypes'])]
    #[ORM\Column(length: 100)]
    #[ApiProperty(example: 'header or footer')]
    #[Groups('menu.get')]
    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Groups('menu.get')]
    private Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }
}
