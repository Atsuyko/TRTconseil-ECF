<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Candidat;
use App\Entity\Recruteur;
use App\Entity\Candidature;
use App\Form\ConsultantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

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

    #[Route('/admin-create-consultant', name: 'app_admin', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createConsultant(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator): Response
    {
        $user = new User();
        $form =  $this->createForm(ConsultantType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_CONSULTANT"]);
            $user->setIsValidate(true);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/create_consultant.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
