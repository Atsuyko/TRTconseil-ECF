<?php

namespace App\Controller;

use App\Entity\Recruteur;
use App\Form\RecruteurType;
use App\Repository\RecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecruteurController extends AbstractController
{

    /**
     * Read, display recruteur
     *
     * @param RecruteurRepository $repository
     * @param int $id
     * @return Response
     */
    #[Route('/recruteur', name: 'app_recruteur', methods: ['GET'])]
    #[Security("is_granted('ROLE_RECRUTEUR') and user === recruteur.getUser()")]
    public function index(RecruteurRepository $repository, Recruteur $id): Response
    {
        $recruteur = $repository->findOneBy(["id" => $id]);
        return $this->render('recruteur/index.html.twig', [
            'recruteur' => $recruteur,
        ]);
    }

    /**
     * Add profil info for create new Recruteur
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recruteur/new', name: 'recruteur.new')]
    #[Security("is_granted('ROLE_RECRUTEUR') and user === recruteur.getUser()")]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $currentUser = $this->getUser();

        $newRecruteur = new Recruteur();
        $form = $this->createForm(RecruteurType::class, $newRecruteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRecruteur->setUser($currentUser);
            $newRecruteur->setCompany($form->get('company')->getData());
            $newRecruteur->setCompanyAdress($form->get('company_adress')->getData());
            $newRecruteur->setCompanyPostcode($form->get('company_postcode')->getData());
            $newRecruteur->setCompanyCity($form->get('company_city')->getData());

            $manager->persist($newRecruteur);
            $manager->flush();

            return $this->redirectToRoute('app_annonce');
        }
        return $this->render('recruteur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Update, update a recruteur
     *
     * @param RecruteurRepository $repository
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recruteur/edit/{id}', 'recruteur.edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_RECRUTEUR') and user === recruteur.getUser()")]
    public function edit(RecruteurRepository $recruteurRepository, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $recruteur = $recruteurRepository->findOneBy(["id" => $id]);
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recruteur = $form->getData();

            $manager->persist($recruteur);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre profil a bien été modifiée !'
            );

            return $this->redirectToRoute('app_recruteur');
        }

        return $this->render('recruteur/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
