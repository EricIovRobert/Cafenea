<?php

namespace App\Controller;

use App\Entity\Locatie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class LocatieController extends AbstractController
{
    #[Route('/locatii', name: 'locatii')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $locatii = $entityManager->getRepository(Locatie::class)->findAll();

        return $this->render('locatii/index.html.twig', [
            'locatii' => $locatii,
        ]);
    }

    #[Route('/locatii/add', name: 'locatii_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $locatie = new Locatie();

        $form = $this->createFormBuilder($locatie)
            ->add('Nr_aparat', IntegerType::class, [
                'label' => 'Număr Aparat',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('Nume', TextType::class, [
                'label' => 'Nume Locație',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($locatie);
            $entityManager->flush();

            $this->addFlash('success', 'Locația a fost adăugată cu succes!');
            return $this->redirectToRoute('locatii');
        }

        return $this->render('locatii/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/locatii/edit/{id}', name: 'locatii_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $locatie = $entityManager->getRepository(Locatie::class)->find($id);

        if (!$locatie) {
            throw $this->createNotFoundException('Locația nu a fost găsită!');
        }

        $form = $this->createFormBuilder($locatie)
            ->add('Nr_aparat', IntegerType::class, [
                'label' => 'Număr Aparat',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('Nume', TextType::class, [
                'label' => 'Nume Locație',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Locația a fost actualizată cu succes!');
            return $this->redirectToRoute('locatii');
        }

        return $this->render('locatii/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/locatii/delete/{id}', name: 'locatii_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $locatie = $entityManager->getRepository(Locatie::class)->find($id);

        if (!$locatie) {
            throw $this->createNotFoundException('Locația nu a fost găsită!');
        }

        $entityManager->remove($locatie);
        $entityManager->flush();

        $this->addFlash('success', 'Locația a fost ștearsă cu succes!');
        return $this->redirectToRoute('locatii');
    }
}
