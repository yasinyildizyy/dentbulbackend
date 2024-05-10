<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\GalleryCategory;
use App\Field\TranslatableField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class GalleryCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GalleryCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('gallery')
            ->setEntityLabelInPlural('galleries')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->overrideTemplate('crud/edit', 'admin/crud/gallery_category/edit_crud.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel()->hideOnIndex();
        yield IdField::new('id', 'ID')
            ->hideOnForm();
        yield TranslatableField::new('name', 'name')
            ->setFormTypeOption('type', TextType::class);
        yield TextField::new('slug', 'slug')
            ->setRequired(false)
            ->setHelp('help.slug');
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
