<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\Slider;
use App\Field\TranslatableField;
use Bigoen\AdminTwoThemeBundle\Field\VichImageField;
use Bigoen\GedmoTranslationFormBundle\Form\TranslatableType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class SliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slider::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('slider')
            ->setEntityLabelInPlural('sliders')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->setFormOptions(
                ['validation_groups' => ['Default', 'Slider.post']],
                ['validation_groups' => ['Default']],
            );
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm();
        yield ImageField::new('photoUrl', 'photo')
            ->hideOnForm();
        yield VichImageField::new('photoFile', 'photo')
            ->setFormTypeOption('allow_delete', false)
            ->onlyOnForms();
        yield TranslatableField::new('title', 'title')
            ->setFormTypeOption('type', TextType::class);
        yield TranslatableField::new('description', 'description')
            ->setFormTypeOption('type', TextType::class);
        yield TranslatableField::new('buttonName', 'buttonName')
            ->setFormTypeOption('type', TextType::class);
        yield TranslatableField::new('buttonUrl', 'buttonUrl')
            ->setFormTypeOption('type', UrlType::class)
            ->hideOnIndex();
        yield NumberField::new('position', 'position')
            ->setHelp('help.position');
        yield BooleanField::new('isActive', 'isActive');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
