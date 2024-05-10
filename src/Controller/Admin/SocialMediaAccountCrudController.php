<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\SocialMediaAccount;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class SocialMediaAccountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SocialMediaAccount::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('socialMediaAccount')
            ->setEntityLabelInPlural('socialMediaAccounts')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->hideOnForm();
        yield TextField::new('username', 'username');
        yield TextField::new('iconName', 'iconName');
        yield UrlField::new('url', 'url')->hideOnIndex();
        yield NumberField::new('position', 'position')->setHelp('help.position');
        yield BooleanField::new('isActive', 'isActive');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
