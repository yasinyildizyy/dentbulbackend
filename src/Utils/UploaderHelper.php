<?php

declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper as BaseHelper;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class UploaderHelper
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly BaseHelper $helper
    ) {}

    public function asset($obj, ?string $fieldName = null): ?string
    {
        $baseUrl = null;
        $request = $this->requestStack->getCurrentRequest();
        if ($request instanceof Request) {
            $uri = $request->getUri();
            $parser = parse_url($uri);
            if (isset($parser['scheme'], $parser['host'])) {
                $baseUrl = sprintf(
                    '%s://%s',
                    $parser['scheme'],
                    $parser['host']
                );
            }
        }
        $path = $this->helper->asset($obj, $fieldName);

        return is_string($path) ? "$baseUrl/$path" : null;
    }
}
