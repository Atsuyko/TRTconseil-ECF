<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class CandidatController extends AbstractController
{

    /**
     * Read, display candidat
     *
     * @param CandidatRepository $repository
     * @return Response
     */
    #[Route('/candidat', name: 'app_candidat', methods: ['GET'])]
    // #[IsGranted(new Expression("is_granted('ROLE_CANDIDAT') and user === candidat.getUser()"))]
    public function index(CandidatRepository $candidatRepository): Response
    {
        $currentUser = $this->getUser();
        $candidats = $candidatRepository->findAll();
        foreach ($candidats as $candidat) {
            if ($candidat->getUser() === $currentUser) {
                $currentCandidat = $candidat;
            }
        }


        return $this->render('candidat/index.html.twig', [
            'candidat' => $currentCandidat,
        ]);
    }

    /**
     * Add profil info for create new candidat
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/candidat/new', name: 'candidat.new')]
    // #[IsGranted(new Expression("is_granted('ROLE_CANDIDAT') and user === candidat.getUser()"))]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $currentUser = $this->getUser();

        $newCandidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $newCandidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newCandidat->setUser($currentUser);
            $newCandidat->setNom($form->get('nom')->getData());
            $newCandidat->setPrenom($form->get('prenom')->getData());
            $newCandidat->setCv(true);

            $cv = $form->get('cv')->getData();
            $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $cv->guessExtension();
            try {
                $cv->move(
                    $this->getParameter('cv_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }

            $newCandidat->setCv($newFilename);
            $manager->persist($newCandidat);
            $manager->flush();

            return $this->redirectToRoute('app_annonce');
        }
        return $this->render('candidat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Update, update a candidat
     *
     * @param CandidatRepository $repository
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/candidat/edit/{id}', 'candidat.edit', methods: ['GET', 'POST'])]
    // #[IsGranted(new Expression("is_granted('ROLE_CANDIDAT') and user === candidat.getUser()"))]
    public function edit(CandidatRepository $candidatRepository, int $id, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $candidatCv = '';
        $candidat = $candidatRepository->findOneBy(["id" => $id]);
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidat = $form->getData();

            $cv = $form->get('cv')->getData();
            $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $cv->guessExtension();
            try {
                $cv->move(
                    $this->getParameter('cv_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }

            $candidat->setCv($newFilename);
            $manager->persist($candidat);
            $manager->flush();

            return $this->redirectToRoute('app_candidat');
        }

        return $this->render('candidat/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
