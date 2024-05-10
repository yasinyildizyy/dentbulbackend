<?php

declare(strict_types=1);

namespace App\Model\Admin;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
#[Vich\Uploadable]
final class VichImageEditor
{
    private ?string $path = null;

    #[Assert\Image]
    #[Vich\UploadableField(mapping: 'editor', fileNameProperty: 'path')]
    private ?File $file = null;

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }
}
