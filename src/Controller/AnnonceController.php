<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AnnonceController extends AbstractController
{
    /**
     * Read, display all annonces
     *
     * @param AnnonceRepository $repository
     * @return Response
     */
    #[Route('/annonce', name: 'app_annonce', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(AnnonceRepository $repository): Response
    {
        $annonces = $repository->findAll();
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    /**
     * Create, Add a new annonce
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/annonce/new', 'annonce.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_RECRUTEUR')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {

        $recruteur = $this->getUser();

        $newAnnonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $newAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newAnnonce->setUser($recruteur);
            $newAnnonce->setJobTitle($form->get('job_title')->getData());
            $newAnnonce->setWorkPlace($form->get('work_place')->getData());
            $newAnnonce->setDescription($form->get('description')->getData());

            $manager->persist($newAnnonce);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre annonce a été transmise à un consultant TRT Conseil pour validation...'
            );

            return $this->redirectToRoute('app_annonce');
        }

        return $this->render('annonce/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Update, update an annonce
     *
     * @param AnnonceRepository $repository
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/annonce/edit/{id}', 'annonce.edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_RECRUTEUR') and user === annonce.getUser()")]
    public function edit(AnnonceRepository $annonceRepository, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $annonce = $annonceRepository->findOneBy(["id" => $id]);
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce = $form->getData();

            $manager->persist($annonce);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre annonce a bien été modifiée !'
            );

            return $this->redirectToRoute('app_annonce');
        }

        return $this->render('annonce/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/annonce/delete/{id}', 'annonce.delete', methods: ['GET'])]
    #[Security("(is_granted('ROLE_RECRUTEUR') and user === annonce.getUser()) or (is_granted('ROLE_CONSULTANT')) or (is_granted('ROLE_ADMIN'))")]
    public function delete(EntityManagerInterface $manager, AnnonceRepository $annonceRepository, int $id): Response
    {
        $annonce = $annonceRepository->findOneBy(["id" => $id]);
        $manager->remove($annonce);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre annonce a bien été supprimée !'
        );

        return $this->redirectToRoute('app_annonce');
    }
}
