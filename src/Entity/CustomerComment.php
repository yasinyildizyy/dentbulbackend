<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\PositionTrait;
use App\Repository\CustomerCommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerCommentRepository::class)]
#[Gedmo\SoftDeleteable]
#[ApiResource(
    operations: [
        new GetCollection(
            filters: ['position.order_filter', 'is_show_homepage.boolean_filter'],
        ),
    ],
    normalizationContext: ['groups' => ['customer_comment.get']],
    order: ['position' => 'ASC'],
)]
class CustomerComment extends AbstractEntity implements Translatable
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
    #[Groups('customer_comment.get')]
    private ?string $fullName = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Gedmo\Translatable]
    #[Groups('customer_comment.get')]
    private ?string $comment = null;

    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('customer_comment.get')]
    private ?string $videoId = null;

    #[ORM\Column]
    private bool $isShowHomepage = false;

    #[Assert\NotBlank]
    #[Assert\Range(min: 1900, max: 2100)]
    #[ORM\Column]
    #[Groups('customer_comment.get')]
    private ?int $year = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    #[Groups('customer_comment.get')]
    private ?string $countryName = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    #[Groups('customer_comment.get')]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'customerComment', targetEntity: CustomerCommentPhoto::class, orphanRemoval: true)]
    #[Groups('customer_comment.get')]
    private Collection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

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

    /**
     * @return Collection<int, CustomerCommentPhoto>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(CustomerCommentPhoto $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setCustomerComment($this);
        }

        return $this;
    }

    public function removePhoto(CustomerCommentPhoto $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCustomerComment() === $this) {
                $photo->setCustomerComment(null);
            }
        }

        return $this;
    }
}
