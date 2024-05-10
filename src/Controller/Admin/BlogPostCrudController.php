<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\BlogPost;
use App\Field\CKEditorField;
use App\Field\TranslatableField;
use Bigoen\AdminTwoThemeBundle\Field\VichImageField;
use Bigoen\GedmoTranslationFormBundle\Form\TranslatableType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class BlogPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogPost::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('blogPost')
            ->setEntityLabelInPlural('blogPosts')
            ->setDefaultSort(['writeAt' => 'DESC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->setFormOptions(
                ['validation_groups' => ['Default', 'BlogPost.post']],
                ['validation_groups' => ['Default']],
            );
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->hideOnForm();
        yield ImageField::new('photoUrl', 'photo')->hideOnForm();
        yield VichImageField::new('photoFile', 'photo')
            ->setFormTypeOption('allow_delete', false)
            ->onlyOnForms();
        yield AssociationField::new('parent', 'parent');
        yield TranslatableField::new('title', 'title')
            ->setFormTypeOption('type', TextType::class);
        yield TextField::new('slug', 'slug')
            ->setRequired(false)
            ->setHelp('help.slug')
            ->hideOnIndex();
        yield TranslatableField::new('description', 'description')
            ->setFormTypeOption('type', TextType::class)
            ->hideOnIndex();
        yield CKEditorField::new('body', 'body')
            ->setFormType(TranslatableType::class)
            ->setFormTypeOption('type', CKEditorType::class);
        yield DateTimeField::new('writeAt', 'writeAt');
        yield BooleanField::new('isActive', 'isActive');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
