<?php

declare(strict_types=1);

namespace App\Controller\Api;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\CustomerComment;
use App\Entity\CustomerCommentPhoto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class CustomerCommentPhotoPostController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(Request $request): CustomerCommentPhoto
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile instanceof UploadedFile) {
            throw new BadRequestHttpException('"file" is required!');
        }
        $customerCommentId = $request->get('customerCommentId');
        if (!$customerCommentId) {
            throw new NotFoundHttpException('Customer comment id not found!');
        }
        $customerComment = $this->entityManager->getRepository(CustomerComment::class)->find($customerCommentId);
        if (!$customerComment instanceof CustomerComment) {
            throw new NotFoundHttpException('Customer comment not found!');
        }
        $object = (new CustomerCommentPhoto())->setCustomerComment($customerComment)->setFile($uploadedFile);
        // validate.
        $this->validator->validate($object);

        return $object;
    }
}
