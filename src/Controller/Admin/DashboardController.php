<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\Post;
use App\Entity\Seller;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony TP');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Back Home');
        yield MenuItem::linkToRoute('Return to the site', 'fa-solid fa-arrow-left', 'app_home');
        yield MenuItem::section('Administration');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Sellers');
        yield MenuItem::linkToCrud('Seller', 'fa-solid fa-store', Seller::class);
        yield MenuItem::linkToCrud('Booking', 'fa-solid fa-calendar-check', Booking::class);
        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('Post', 'fa-brands fa-wordpress', Post::class);
        yield MenuItem::linkToCrud('Tag', 'fa-solid fa-tag', Tag::class);
    }
}
