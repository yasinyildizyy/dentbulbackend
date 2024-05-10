<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\PositionTrait;
use App\EventListener\Doctrine\FaqGroupListener;
use App\Repository\FaqGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FaqGroupRepository::class)]
#[ORM\EntityListeners([FaqGroupListener::class])]
#[Gedmo\SoftDeleteable]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ],
    normalizationContext: ['groups' => ['faq.get']],
    filters: ['position.order_filter'],
    order: ['position' => 'ASC'],
    paginationClientEnabled: false,
    paginationEnabled: false,
)]
class FaqGroup extends AbstractEntity implements Translatable
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
    #[Groups('faq.get')]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'faqGroup', targetEntity: Faq::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Groups('faq.get')]
    private Collection $faqs;

    #[ORM\OneToMany(mappedBy: 'faqGroup', targetEntity: Cure::class)]
    private Collection $cures;

    public function __construct()
    {
        $this->faqs = new ArrayCollection();
        $this->cures = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
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

    /**
     * @return Collection<int, Faq>
     */
    public function getFaqs(): Collection
    {
        return $this->faqs;
    }

    public function addFaq(Faq $faq): self
    {
        if (!$this->faqs->contains($faq)) {
            $this->faqs->add($faq);
            $faq->setFaqGroup($this);
        }

        return $this;
    }

    public function removeFaq(Faq $faq): self
    {
        if ($this->faqs->removeElement($faq)) {
            // set the owning side to null (unless already changed)
            if ($faq->getFaqGroup() === $this) {
                $faq->setFaqGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cure>
     */
    public function getCures(): Collection
    {
        return $this->cures;
    }
}
