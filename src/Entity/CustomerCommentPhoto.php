<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Api\CustomerCommentPhotoPostController;
use App\Entity\Traits\PositionTrait;
use App\EventListener\Doctrine\CustomerCommentPhotoListener;
use App\Repository\CustomerCommentPhotoRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CustomerCommentPhotoRepository::class)]
#[ORM\EntityListeners([CustomerCommentPhotoListener::class])]
#[Gedmo\SoftDeleteable]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: 'admin/customer_comment_photos/{id}',
            security: "is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(
            uriTemplate: 'admin/customer_comment_photos',
            security: "is_granted('ROLE_ADMIN')",
            filters: ['customer_comment_photo.search_filter'],
        ),
        new Put(
            uriTemplate: 'admin/customer_comment_photos/{id}',
            denormalizationContext: ['groups' => ['admin.customer_comment.put']],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Post(
            uriTemplate: 'admin/customer_comment_photos',
            controller: CustomerCommentPhotoPostController::class,
            openapiContext: [
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'customerCommentId',
                        'description' => 'Customer comment id',
                        'type' => 'integer',
                        'required' => true,
                    ],
                ],
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            security: "is_granted('ROLE_ADMIN')",
            deserialize: false,
        ),
        new Delete(
            uriTemplate: 'admin/customer_comment_photos/{id}',
            security: "is_granted('ROLE_ADMIN')",
        ),
    ],
    normalizationContext: ['groups' => ['admin.customer_comment.get']],
    denormalizationContext: ['groups' => ['admin.customer_comment.post']],
    order: ['position' => 'ASC'],
)]
class CustomerCommentPhoto extends AbstractEntity
{
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('admin.customer_comment.get')]
    private ?int $id = null;

    #[Assert\NotNull(groups: ['CustomerComment.post'])]
    #[Assert\Image(maxSize: '1M')]
    #[Vich\UploadableField(mapping: 'customer_comment', fileNameProperty: 'path')]
    #[Groups(['admin.customer_comment.post'])]
    private ?File $file = null;

    #[ORM\Column(length: 300, nullable: true)]
    #[Groups('admin.customer_comment.get')]
    private ?string $path = null;

    #[Groups(['customer_comment.get', 'admin.customer_comment.get'])]
    public ?string $url = null;

    #[ORM\Column(options: ['default' => 1])]
    #[Groups('admin.customer_comment.put')]
    protected int $position = 1;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomerComment $customerComment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;
        $this->updatedAt = new DateTime();

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

    public function getCustomerComment(): ?CustomerComment
    {
        return $this->customerComment;
    }

    public function setCustomerComment(?CustomerComment $customerComment): self
    {
        $this->customerComment = $customerComment;

        return $this;
    }
}
