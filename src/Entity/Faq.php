<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\GedmoLocaleTrait;
use App\Entity\Traits\PositionTrait;
use App\Repository\FaqRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FaqRepository::class)]
#[Gedmo\SoftDeleteable]
class Faq extends AbstractEntity implements Translatable
{
    use GedmoLocaleTrait;
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 0, max: 300)]
    #[ORM\Column(length: 300)]
    #[Gedmo\Translatable]
    #[Groups('faq.get')]
    private ?string $question = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Gedmo\Translatable]
    #[Groups('faq.get')]
    private ?string $answer = null;

    #[ORM\ManyToOne(inversedBy: 'faqs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FaqGroup $faqGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

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
}
