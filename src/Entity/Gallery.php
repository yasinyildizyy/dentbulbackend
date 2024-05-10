<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Api\GalleryPostController;
use App\Entity\Traits\PositionTrait;
use App\EventListener\Doctrine\GalleryListener;
use App\Repository\GalleryRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
#[ORM\EntityListeners([GalleryListener::class])]
#[Gedmo\SoftDeleteable]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: 'admin/galleries/{id}',
            security: "is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(
            uriTemplate: 'admin/galleries',
            security: "is_granted('ROLE_ADMIN')",
            filters: ['gallery.search_filter'],
        ),
        new Put(
            uriTemplate: 'admin/galleries/{id}',
            denormalizationContext: ['groups' => ['admin.gallery.put']],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Post(
            uriTemplate: 'admin/galleries',
            controller: GalleryPostController::class,
            openapiContext: [
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'galleryCategoryId',
                        'description' => 'Gallery category id',
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
            uriTemplate: 'admin/galleries/{id}',
            security: "is_granted('ROLE_ADMIN')",
        ),
    ],
    normalizationContext: ['groups' => ['admin.gallery.get']],
    denormalizationContext: ['groups' => ['admin.gallery.post']],
    order: ['position' => 'ASC'],
)]
class Gallery extends AbstractEntity
{
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('admin.gallery.get')]
    private ?int $id = null;

    #[Assert\NotNull(groups: ['Gallery.post'])]
    #[Assert\Image(maxSize: '1M')]
    #[Vich\UploadableField(mapping: 'gallery', fileNameProperty: 'path')]
    #[Groups(['admin.gallery.post'])]
    private ?File $file = null;

    #[ORM\Column(length: 300, nullable: true)]
    #[Groups('admin.gallery.get')]
    private ?string $path = null;

    #[Groups(['gallery.get', 'admin.gallery.get'])]
    public ?string $url = null;

    #[ORM\Column]
    #[Groups(['gallery.get', 'admin.gallery.put'])]
    protected int $position = 1;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(inversedBy: 'galleries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GalleryCategory $category = null;

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

    public function getCategory(): ?GalleryCategory
    {
        return $this->category;
    }

    public function setCategory(?GalleryCategory $category): self
    {
        $this->category = $category;

        return $this;
    }
}
