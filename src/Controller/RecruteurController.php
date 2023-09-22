<?php

namespace App\Controller;

use App\Entity\Recruteur;
use App\Form\RecruteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecruteurController extends AbstractController
{
    /**
     * Add profil info for create new Recruteur
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recruteur', name: 'app_recruteur')]
    public function index(Request $request, EntityManagerInterface $manager): Response
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
        return $this->render('recruteur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
