<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\FaqGroup;
use App\Field\TranslatableField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class FaqGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FaqGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('faqGroup')
            ->setEntityLabelInPlural('faqGroups')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->setFormOptions(
                ['validation_groups' => ['Default', 'Page.post']],
                ['validation_groups' => ['Default']],
            )
            ->overrideTemplates([
                'crud/detail' => 'admin/crud/faq_group/detail_crud.html.twig',
                'crud/edit' => 'admin/crud/faq_group/edit_crud.html.twig',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm();
        yield TranslatableField::new('title', 'title')
            ->setFormTypeOption('type', TextType::class);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->disable(Action::DETAIL, Action::SAVE_AND_ADD_ANOTHER);
    }
}
