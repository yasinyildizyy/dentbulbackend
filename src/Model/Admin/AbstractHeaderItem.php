<?php

declare(strict_types=1);

namespace App\Model\Admin;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
abstract class AbstractHeaderItem
{
    public ?string $route = null;
    public ?array $routeParams = null;
    public ?string $controller = null;
    public ?string $action = null;
    public ?array $parameters = null;
    public ?array $options = null;
    public ?string $name = null;
    public ?string $icon = null;
    public ?int $id = null;
    public ?string $url = null;

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
