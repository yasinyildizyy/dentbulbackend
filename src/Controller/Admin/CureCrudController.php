<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\Cure;
use App\Field\CKEditorField;
use App\Field\TranslatableField;
use Bigoen\AdminTwoThemeBundle\Field\VichImageField;
use Bigoen\GedmoTranslationFormBundle\Form\TranslatableType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class CureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cure::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('cure')
            ->setEntityLabelInPlural('cures')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->setFormOptions(
                ['validation_groups' => ['Default', 'Cure.post']],
                ['validation_groups' => ['Default']],
            )
            ->overrideTemplates([
                'crud/detail' => 'admin/crud/cure/detail_crud.html.twig',
                'crud/edit' => 'admin/crud/cure/edit_crud.html.twig',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $photoUrl = ImageField::new('photoUrl', 'photo');
        $photoFile =  VichImageField::new('photoFile', 'photo')
            ->setFormTypeOption('allow_delete', false);
        $faqGroup =  AssociationField::new('faqGroup', 'faqGroup');
        $name = TranslatableField::new('name', 'name')
            ->setFormTypeOption('type', TextType::class);
        $slug = TextField::new('slug', 'slug')
            ->setRequired(false)
            ->setHelp('help.slug');
        $shortDescription = TranslatableField::new('shortDescription', 'shortDescription')
            ->setFormTypeOption('type', TextareaType::class)
            ->hideOnIndex();
        $longDescription = CKEditorField::new('longDescription', 'longDescription')
            ->setFormType(TranslatableType::class)
            ->setFormTypeOption('type', CKEditorType::class);
        $videoId = TextField::new('videoId', 'videoId')
            ->setHelp('help.videoId');
        $position = NumberField::new('position', 'position')
            ->setHelp('help.position');
        $isShowHomepage = BooleanField::new('isShowHomepage', 'isShowHomepage');
        $isActive = BooleanField::new('isActive', 'isActive');

        return match ($pageName) {
            Crud::PAGE_INDEX => [$id, $photoUrl, $name, $position, $isShowHomepage, $isActive],
            default => [$photoFile, $faqGroup, $name, $slug, $shortDescription, $longDescription, $videoId, $position, $isShowHomepage, $isActive],
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
