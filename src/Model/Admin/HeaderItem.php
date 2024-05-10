<?php

declare(strict_types=1);

namespace App\Model\Admin;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class HeaderItem extends AbstractHeaderItem
{
    public ?int $mainId = null;

    public static function new(
        string $controller,
        string $action,
        string $name,
        string $icon,
        ?int $id = null,
        ?int $mainId = null,
        ?array $parameters = null,
        ?array $options = null
    ): self {
        $object = new self();
        $object->controller = $controller;
        $object->action = $action;
        $object->name = $name;
        $object->icon = $icon;
        $object->id = $id;
        $object->mainId = $mainId;
        $object->parameters = $parameters;
        $object->options = $options;

        return $object;
    }
}
