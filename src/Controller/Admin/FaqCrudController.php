<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\Faq;
use App\Entity\FaqGroup;
use App\Field\TranslatableField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityNotFoundException;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class FaqCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Faq::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('faq')
            ->setEntityLabelInPlural('faqs')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->setFormOptions(
                ['validation_groups' => ['Default', 'Page.post']],
                ['validation_groups' => ['Default']],
            )
            ->overrideTemplates([
                'crud/index' => 'admin/crud/faq_group/relation_index_crud.html.twig',
                'crud/action' => 'admin/crud/faq_group/relation_action_crud.html.twig',
                'crud/detail' => 'admin/crud/faq_group/relation_detail_crud.html.twig',
                'crud/edit' => 'admin/crud/faq_group/relation_edit_crud.html.twig',
                'crud/new' => 'admin/crud/faq_group/relation_new_crud.html.twig',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm();
        yield TranslatableField::new('question', 'question')
            ->setFormTypeOption('type', TextType::class);
        yield TranslatableField::new('answer', 'answer')
            ->setFormTypeOption('type', TextareaType::class);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::DETAIL);
    }

    public function createEntity(string $entityFqcn): Faq
    {
        $faqGroup = $this->findOneByMainId(FaqGroup::class);
        if (!$faqGroup instanceof FaqGroup) {
            throw new EntityNotFoundException();
        }

        return (new Faq())->setFaqGroup($faqGroup);
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if (!($mainId = $this->getRequestParameter('mainId'))) {
            throw new AccessDeniedHttpException();
        }

        return $queryBuilder
            ->andWhere('entity.faqGroup = :mainId')
            ->setParameter('mainId', $mainId);
    }
}
