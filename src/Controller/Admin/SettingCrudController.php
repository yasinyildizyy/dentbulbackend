<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\Setting;
use App\Form\SettingExtensionType;
use Bigoen\AdminTwoThemeBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('setting')
            ->setEntityLabelInPlural('settings')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false);
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel();
        yield IdField::new('id', 'ID')->hideOnForm();
        yield TextField::new('uniqueName', 'uniqueName')->setHelp('help.uniqueName');
        yield FormField::addPanel('extensions');
        yield CollectionField::new('extensions', '')
            ->setEntryType(SettingExtensionType::class)
            ->isStandard()
            ->hideOnIndex();
    }
}
