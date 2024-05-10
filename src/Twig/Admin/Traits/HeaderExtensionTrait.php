<?php

declare(strict_types=1);

namespace App\Twig\Admin\Traits;

use App\Model\Admin\AbstractHeaderItem;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
trait HeaderExtensionTrait
{
    private function headerCreate(array $arr): array
    {
        // set referer.
        $referer = null;
        $request = $this->getRequest();
        if ($request instanceof Request && $request->headers->has('referer')) {
            $referer = $request->headers->get('referer');
        }

        return array_map(function (AbstractHeaderItem $item) use ($referer, $request) {
            if (null !== $item->controller && null !== $item->action) {
                $build = $this->adminUrlGenerator->unsetAll()->setController($item->controller)->setAction($item->action);
            } else {
                $build = $this->adminUrlGenerator->unsetAll()->setRoute($item->route, $item->routeParams);
            }
            if (\is_array($item->parameters)) {
                foreach ($item->parameters as $key => $value) {
                    $build->set($key, $value);
                }
            }
            if (\is_int($item->id)) {
                $build->set('entityId', $item->id);
            }
            if (property_exists($item, 'mainId') && \is_int($item->mainId)) {
                $build->set('mainId', $item->mainId);
            }
            if (\is_string($referer)) {
                $build->set('referrer', $referer);
            }

            return $item->setUrl($build->generateUrl());
        }, $arr);
    }

    protected function getRequestParameter(string $key): ?string
    {
        $request = $this->getRequest();
        if (!$request instanceof Request) {
            return null;
        }
        $parameter = $request->get($key);

        return \is_string($parameter) ? $parameter : null;
    }

    protected function getRequest(): ?Request
    {
        return $this->requestStack->getMainRequest() ?? $this->requestStack->getCurrentRequest();
    }
}
