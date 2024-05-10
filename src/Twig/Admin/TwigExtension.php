<?php

declare(strict_types=1);

namespace App\Twig\Admin;

use App\Controller\Admin\CureCrudController;
use App\Controller\Admin\CureSubCrudController;
use App\Controller\Admin\FaqCrudController;
use App\Controller\Admin\FaqGroupCrudController;
use App\Controller\Admin\PageCrudController;
use App\Controller\Admin\PageSubCrudController;
use App\Entity\Cure;
use App\Entity\FaqGroup;
use App\Entity\Page;
use App\Model\Admin\HeaderItem;
use App\Twig\Admin\Traits\HeaderExtensionTrait;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class TwigExtension extends AbstractExtension
{
    use HeaderExtensionTrait;

    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('adminCureHeader', [$this, 'getCureHeader']),
            new TwigFunction('adminPageHeader', [$this, 'getPageHeader']),
            new TwigFunction('adminFaqHeader', [$this, 'getFaqHeader']),
            new TwigFunction('adminMainObjectFromRequest', [$this, 'getMainObjectFromRequest']),
        ];
    }

    public function getCureHeader(Cure $cure): array
    {
        $t = $this->translator;
        $mainId = $cure->getId();

        return $this->headerCreate([
            HeaderItem::new(
                CureCrudController::class,
                Crud::PAGE_EDIT,
                $t->trans('edit', [], 'admin'),
                'pen',
                $mainId
            ),
            HeaderItem::new(
                CureSubCrudController::class,
                Crud::PAGE_INDEX,
                $t->trans('subs', [], 'admin'),
                'bars',
                null,
                $mainId
            ),
        ]);
    }

    public function getPageHeader(Page $page): array
    {
        $t = $this->translator;
        $mainId = $page->getId();

        return $this->headerCreate([
            HeaderItem::new(
                PageCrudController::class,
                Crud::PAGE_EDIT,
                $t->trans('edit', [], 'admin'),
                'pen',
                $mainId
            ),
            HeaderItem::new(
                PageSubCrudController::class,
                Crud::PAGE_INDEX,
                $t->trans('subs', [], 'admin'),
                'bars',
                null,
                $mainId
            ),
        ]);
    }

    public function getFaqHeader(FaqGroup $faqGroup): array
    {
        $t = $this->translator;
        $mainId = $faqGroup->getId();

        return $this->headerCreate([
            HeaderItem::new(
                FaqGroupCrudController::class,
                Crud::PAGE_EDIT,
                $t->trans('edit', [], 'admin'),
                'pen',
                $mainId
            ),
            HeaderItem::new(
                FaqCrudController::class,
                Crud::PAGE_INDEX,
                $t->trans('faqs', [], 'admin'),
                'bars',
                null,
                $mainId
            ),
        ]);
    }

    public function getMainObjectFromRequest(Request $request, string $mainClass): ?object
    {
        $id = $request->get('mainId');
        if (null === $id) {
            return null;
        }

        return $this->entityManager->getRepository($mainClass)->find($id);
    }
}
