<?php

declare(strict_types=1);

namespace App\Controller\Api;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Gallery;
use App\Entity\GalleryCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class GalleryPostController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(Request $request): Gallery
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile instanceof UploadedFile) {
            throw new BadRequestHttpException('"file" is required!');
        }
        $galleryCategoryId = $request->get('galleryCategoryId');
        if (!$galleryCategoryId) {
            throw new NotFoundHttpException('Gallery category id not found!');
        }
        $galleryCategory = $this->entityManager->getRepository(GalleryCategory::class)->find($galleryCategoryId);
        if (!$galleryCategory instanceof GalleryCategory) {
            throw new NotFoundHttpException('Gallery category not found!');
        }
        $object = (new Gallery())->setCategory($galleryCategory)->setFile($uploadedFile);
        // validate.
        $this->validator->validate($object);

        return $object;
    }
}
