<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Entity\Candidat;
use App\Entity\Candidature;
use App\Entity\Recruteur;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TRTconseil ECF - Admin Panel')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Recruteurs', 'fas fa-building', Recruteur::class);
        yield MenuItem::linkToCrud('Candidats', 'fas fa-person-chalkboard', Candidat::class);
        yield MenuItem::linkToCrud('Annonces', 'fas fa-bullhorn', Annonce::class);
        yield MenuItem::linkToCrud('Candidature', 'fas fa-scroll', Candidature::class);
    }
}
