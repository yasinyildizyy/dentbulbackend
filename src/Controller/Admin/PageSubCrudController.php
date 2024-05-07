<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\UserConstant;
use App\Entity\Page;
use App\Entity\PageSub;
use App\Field\CKEditorField;
use App\Field\TranslatableField;
use Bigoen\AdminTwoThemeBundle\Field\VichImageField;
use Bigoen\GedmoTranslationFormBundle\Form\TranslatableType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class PageSubCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PageSub::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('sub')
            ->setEntityLabelInPlural('subs')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false)
            ->overrideTemplates([
                'crud/index' => 'admin/crud/page/relation_index_crud.html.twig',
                'crud/action' => 'admin/crud/page/relation_action_crud.html.twig',
                'crud/detail' => 'admin/crud/page/relation_detail_crud.html.twig',
                'crud/edit' => 'admin/crud/page/relation_edit_crud.html.twig',
                'crud/new' => 'admin/crud/page/relation_new_crud.html.twig',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm();
        yield ImageField::new('photoUrl', 'photo')
            ->hideOnForm();
        yield VichImageField::new('photoFile', 'photo')
            ->onlyOnForms();
        yield TranslatableField::new('title', 'title')
            ->setFormTypeOption('type', TextType::class);
        yield CKEditorField::new('description', 'description')
            ->setFormType(TranslatableType::class)
            ->setFormTypeOption('type', CKEditorType::class);
        yield NumberField::new('position', 'position')
            ->setHelp('help.position');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::DETAIL);
    }

    public function createEntity(string $entityFqcn): PageSub
    {
        $page = $this->findOneByMainId(Page::class);
        if (!$page instanceof Page) {
            throw new EntityNotFoundException();
        }

        return (new PageSub())->setPage($page);
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
            ->andWhere('entity.page = :mainId')
            ->setParameter('mainId', $mainId);
    }
}
