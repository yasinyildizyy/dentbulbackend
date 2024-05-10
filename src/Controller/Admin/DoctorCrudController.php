<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\Doctor;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class DoctorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Doctor::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('doctor')
            ->setEntityLabelInPlural('doctors')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->setFormOptions(
                ['validation_groups' => ['Default', 'Doctor.post']],
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
        yield TextField::new('fullName', 'fullName');
        yield TranslatableField::new('title', 'doctorTitle')
            ->setFormTypeOption('type', TextType::class);
        yield NumberField::new('position', 'position')
            ->setHelp('help.position');
        yield BooleanField::new('isActive', 'isActive');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
