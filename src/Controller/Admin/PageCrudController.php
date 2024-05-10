<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\Page;
use App\Field\CKEditorField;
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
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('page')
            ->setEntityLabelInPlural('pages')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->setFormOptions(
                ['validation_groups' => ['Default', 'Page.post']],
                ['validation_groups' => ['Default']],
            )
            ->overrideTemplates([
                'crud/detail' => 'admin/crud/page/detail_crud.html.twig',
                'crud/edit' => 'admin/crud/page/edit_crud.html.twig',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $photoUrl = ImageField::new('photoUrl', 'photo');
        $photoFile =  VichImageField::new('photoFile', 'photo')
            ->setFormTypeOption('allow_delete', false);
        $title = TranslatableField::new('title', 'title')
            ->setFormTypeOption('type', TextType::class);
        $slug = TextField::new('slug', 'slug')
            ->setRequired(false)
            ->setHelp('help.slug');
        $description = CKEditorField::new('description', 'description')
            ->setFormType(TranslatableType::class)
            ->setFormTypeOption('type', CKEditorType::class);
        $videoId = TextField::new('videoId', 'videoId')
            ->setHelp('help.videoId');
        $position = NumberField::new('position', 'position')
            ->setHelp('help.position');
        $isActive = BooleanField::new('isActive', 'isActive');

        return match ($pageName) {
            Crud::PAGE_INDEX => [$id, $photoUrl, $title, $position, $isActive],
            default => [$photoFile, $title, $slug, $description, $videoId, $position, $isActive],
        };
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->disable(Action::DETAIL, Action::SAVE_AND_ADD_ANOTHER);
    }
}
