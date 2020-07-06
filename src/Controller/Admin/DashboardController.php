<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $linkToUserCrud = MenuItem::linkToCrud('Usuarios', 'fa fa-user', User::class);
        return $this->render('dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sistema Vizcarra');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Inicio', 'fa fa-home'),
            MenuItem::section('Usuarios'),
            MenuItem::linkToCrud('Usuarios', 'fa fa-user', User::class),
            MenuItem::section('Sistema'),
            MenuItem::linkToRoute('Caja', 'fa fa-usd', 'caja_index'),
            MenuItem::section(),
            MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        ];

    }
}
