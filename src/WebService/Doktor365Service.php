<?php

declare(strict_types=1);

namespace App\WebService;

use App\Entity\ContactForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
final class Doktor365Service
{
    public const POST_URL = 'https://app.doktor365.com.tr/api/cms/form/';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly TranslatorInterface $translator,
        private readonly string $apiKey
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function post(ContactForm $object): void
    {
        $t = $this->translator;
        // split from full name.
        $names = self::splitFullName($object->getFullName());
        // create post items from extensions.
        $items = [];
        foreach ($object->getExtensions() as $extension) {
            if (!isset($extension['title'], $extension['value'])) {
                continue;
            }
            $items[] = [
                'title' => $extension['title'],
                'value' => $extension['value'],
            ];
        }
        $data['WebForm'] = [
            'title' => isset($object->getExtensions()[0])
                ? $t->trans('contactForm.title.smart', ['%locale%' => $object->getLocale()])
                : $t->trans('contactForm.title.classic', ['%locale%' => $object->getLocale()]),
            'phone' => $object->getPhoneNumber(),
            'name' => $names[0] ?? '-',
            'surname' => $names[1] ?? '-',
            'description' => $object->getMessage(),
            'items' => $items,
        ];
        $this->httpClient->request(
            Request::METHOD_POST,
            self::POST_URL.$this->apiKey.'/',
            [
                'headers' => [
                    'application-language' => $object->getLocale(),
                    'x-host' => 'www.dentbul.com',
                ],
                'body' => $data,
            ]
        );
    }

    private static function splitFullName(string $fullName): array
    {
        $fullName = trim($fullName);
        $lastName = (!str_contains($fullName, ' ')) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $fullName);
        $firstName = trim(preg_replace('#'.preg_quote($lastName,'#').'#', '', $fullName));

        return [
            $firstName,
            $lastName,
        ];
    }
}
