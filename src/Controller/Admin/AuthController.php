<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Bigoen\AdminBundle\Controller\AbstractAuthController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
#[Route('/admin/login', name: 'bigoen_admin.login')]
class AuthController extends AbstractAuthController
{
    public function __invoke(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('bigoen_admin');
        }

        return $this->login(
            $request,
            'bigoen_admin',
            'bigoen_admin.login_check',
            '@BigoenAdminTwoTheme/page/login.html.twig',
            [
                'page_title' => $this->translator->trans('admin.title', [], 'BigoenAdminBundle'),
                'username_label' => $this->translator->trans('admin.email', [], 'BigoenAdminBundle'),
                'password_label' => $this->translator->trans('admin.password', [], 'BigoenAdminBundle'),
            ],
        );
    }
}
