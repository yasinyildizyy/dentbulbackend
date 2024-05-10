<?php

declare(strict_types=1);

namespace App\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class CKEditorField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(CKEditorType::class)
            ->setTemplateName('crud/field/text_editor')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
}
