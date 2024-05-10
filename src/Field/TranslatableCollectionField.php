<?php

declare(strict_types=1);

namespace App\Field;

use Bigoen\AdminTwoThemeBundle\Field\CollectionField;
use Bigoen\GedmoTranslationFormBundle\Form\TranslatableType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class TranslatableCollectionField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(TranslatableType::class)
            ->setFormTypeOption('type', CollectionType::class)
            ->setFormTypeOption('type_options', [
                CollectionField::OPTION_ALLOW_ADD => true,
                CollectionField::OPTION_ALLOW_DELETE => true,
                CollectionField::OPTION_PROTOTYPE => true,
                CollectionField::OPTION_BY_REFERENCE => false,
                'attr' => ['class' => CollectionField::CSS_STANDARD],
            ])
            ->addJsFiles(
                'bundles/bigoenadmintwotheme/js/pages/crud/collection/collection.min.js',
                'bundles/bigoenadmintwotheme/js/pages/crud/collection/basic-collection.min.js'
            );
    }
}
