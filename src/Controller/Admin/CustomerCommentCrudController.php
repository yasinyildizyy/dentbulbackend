<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\CustomerComment;
use App\Field\TranslatableField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class CustomerCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CustomerComment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('customerComment')
            ->setEntityLabelInPlural('customerComments')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->setFormOptions(
                ['validation_groups' => ['Default', 'CustomerComment.post']],
                ['validation_groups' => ['Default']],
            )
            ->overrideTemplate('crud/edit', 'admin/crud/customer_comment/edit_crud.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel()->hideOnIndex();
        yield IdField::new('id', 'ID')
            ->hideOnForm();
        yield TextField::new('fullName', 'fullName');
        yield TranslatableField::new('comment', 'comment')
            ->setFormTypeOption('type', TextareaType::class);
        yield TextField::new('videoId', 'videoId')
            ->setHelp('help.videoId')
            ->hideOnIndex();
        yield TranslatableField::new('countryName', 'countryName')
            ->setFormTypeOption('type', TextType::class);
        yield TranslatableField::new('type', 'type')
            ->setFormTypeOption('type', TextType::class);
        yield NumberField::new('year', 'year');
        yield NumberField::new('position', 'position')
            ->setHelp('help.position');
        yield BooleanField::new('isShowHomepage', 'isShowHomepage');
        yield BooleanField::new('isActive', 'isActive');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->disable(Action::DETAIL, Action::SAVE_AND_ADD_ANOTHER);
    }
}
