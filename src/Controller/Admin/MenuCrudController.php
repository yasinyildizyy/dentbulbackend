<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constant\Entity\MenuConstant;
use App\Constant\Entity\UserConstant;
use App\Entity\Menu;
use App\Field\TranslatableField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class MenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('menu')
            ->setEntityLabelInPlural('menus')
            ->setDefaultSort(['position' => 'ASC'])
            ->setEntityPermission(UserConstant::ADMIN)
            ->showEntityActionsInlined(false);
    }

    public function configureFields(string $pageName): iterable
    {
        $translator = $this->translator;
        // fields.
        yield IdField::new('id', 'ID')->hideOnForm();
        yield AssociationField::new('parent', 'parent')
            ->setFormTypeOption('group_by', function (Menu $choice) use ($translator) {
                return $translator->trans("choice.menu.type.{$choice->getType()}", [], 'admin');
            });
        yield TranslatableField::new('name', 'name')
            ->setFormTypeOption('type', TextType::class);
        yield ChoiceField::new('type', 'type')
            ->setChoices(MenuConstant::getTypeChoices());
        yield TranslatableField::new('path', 'path')
            ->setFormTypeOption('type', TextType::class)
            ->setHelp('help.path')
            ->hideOnIndex();
        yield NumberField::new('position', 'position')
            ->setHelp('help.position');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
