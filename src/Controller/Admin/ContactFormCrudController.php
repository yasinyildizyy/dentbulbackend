<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\ContactForm;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ContactFormCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactForm::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('contactForm')
            ->setEntityLabelInPlural('contactForms')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID');
        yield TextField::new('fullName', 'fullName');
        yield EmailField::new('email', 'email');
        yield TelephoneField::new('phoneNumber', 'phoneNumber');
        yield TextField::new('subject', 'subject');
        yield TextEditorField::new('message', 'message');
        yield TextField::new('locale', 'locale');
        yield ArrayField::new('extensions', 'extensions')
            ->setTemplatePath('admin/field/contact_form/extensions.html.twig')
            ->hideOnIndex();
        yield DateTimeField::new('createdAt', 'createdAt');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::EDIT);
    }
}
