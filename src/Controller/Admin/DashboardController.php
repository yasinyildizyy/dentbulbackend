<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\ContactForm;
use App\Entity\Cure;
use App\Entity\CustomerComment;
use App\Entity\Doctor;
use App\Entity\FaqGroup;
use App\Entity\GalleryCategory;
use App\Entity\Menu;
use App\Entity\Page;
use App\Entity\Setting;
use App\Entity\Slider;
use App\Entity\SocialMediaAccount;
use App\Entity\User;
use Bigoen\AdminTwoThemeBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly AdminUrlGenerator $adminUrlGenerator
    ) {}

    #[Route('/admin', name: 'bigoen_admin')]
    public function index(): Response
    {
        return new RedirectResponse(
            $this->adminUrlGenerator
                ->setDashboard(self::class)
                ->setController(BlogPostCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->translator->trans('general.name', [], 'admin'))
            ->setTranslationDomain('admin')
            ->disableUrlSignatures();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('blog');
        yield MenuItem::linkToCrud('blogPosts', 'fa-bars', BlogPost::class);
        yield MenuItem::section('-');
        yield MenuItem::linkToCrud('contactForms', 'fa-bars', ContactForm::class);
        yield MenuItem::linkToCrud('galleries', 'fa-bars', GalleryCategory::class);
        yield MenuItem::linkToCrud('menus', 'fa-bars', Menu::class);
        yield MenuItem::linkToCrud('sliders', 'fa-bars', Slider::class);
        yield MenuItem::linkToCrud('cures', 'fa-bars', Cure::class);
        yield MenuItem::linkToCrud('doctors', 'fa-bars', Doctor::class);
        yield MenuItem::linkToCrud('pages', 'fa-bars', Page::class);
        yield MenuItem::linkToCrud('faqGroups', 'fa-bars', FaqGroup::class);
        yield MenuItem::linkToCrud('customerComments', 'fa-bars', CustomerComment::class);
        yield MenuItem::linkToCrud('socialMediaAccounts', 'fa-bars', SocialMediaAccount::class);
        yield MenuItem::linkToCrud('settings', 'fa-bars', Setting::class);
        yield MenuItem::linkToCrud('users', 'fa-bars', User::class);
    }
}
