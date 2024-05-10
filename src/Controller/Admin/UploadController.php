<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Admin\VichImageEditor;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Handler\UploadHandler;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
#[Route('/admin/upload', name: 'bigoen_admin.upload', methods: ['POST'])]
final class UploadController
{
    public function __construct(
        private readonly UploadHandler $handler,
        private readonly UploaderHelper $helper,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if (!$request->files->has('upload')) {
            throw new InvalidArgumentException();
        }
        // get file.
        $file = $request->files->get('upload');
        if (!$file instanceof UploadedFile) {
            throw new InvalidArgumentException();
        }
        // create entity.
        $entity = (new VichImageEditor())->setFile($file);
        // upload file.
        $this->handler->upload($entity, 'file');
        // get path and url.
        $path = $this->helper->asset($entity, 'file');
        // get base url.
        $baseUrl = null;
        $uri = $request->getUri();
        $parser = parse_url($uri);
        if (isset($parser['scheme'], $parser['host'])) {
            $baseUrl = sprintf(
                '%s://%s',
                $parser['scheme'],
                $parser['host']
            );
        }
        $url = $baseUrl.'/'.$path;

        return new JsonResponse(
            [
                'error' => [
                    'number' => 207,
                    'message' => $this->translator->trans(
                        'upload.success',
                        [
                            '%image%' => $entity->getPath(),
                        ],
                        'admin'
                    ),
                ],
                'fileName' => $entity->getPath(),
                'uploaded' => 1,
                'url' => $url,
            ]
        );
    }
}
