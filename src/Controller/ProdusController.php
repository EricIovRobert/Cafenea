<?php

namespace App\Controller;

use App\Entity\Produs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProdusController extends AbstractController
{
    #[Route('/produse', name: 'produse')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produse = $entityManager->getRepository(Produs::class)->findAll();

        return $this->render('produse/index.html.twig', [
            'produse' => $produse,
        ]);
    }

    #[Route('/produse/add', name: 'produse_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produs = new Produs();

        $form = $this->createFormBuilder($produs)
            ->add('Nume', TextType::class, [
                'label' => 'Nume Produs',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('Stoc', NumberType::class, [
                'label' => 'Stoc',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('Observatii', TextType::class, [
                'label' => 'Observații',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produs);
            $entityManager->flush();

            $this->addFlash('success', 'Produsul a fost adăugat cu succes!');
            return $this->redirectToRoute('produse');
        }

        return $this->render('produse/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produse/edit/{id}', name: 'produse_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $produs = $entityManager->getRepository(Produs::class)->find($id);

        if (!$produs) {
            throw $this->createNotFoundException('Produsul nu a fost găsit!');
        }

        $form = $this->createFormBuilder($produs)
            ->add('Nume', TextType::class, [
                'label' => 'Nume Produs',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('Stoc', NumberType::class, [
                'label' => 'Stoc',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('Observatii', TextType::class, [
                'label' => 'Observații',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Produsul a fost actualizat cu succes!');
            return $this->redirectToRoute('produse');
        }

        return $this->render('produse/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produse/delete/{id}', name: 'produse_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $produs = $entityManager->getRepository(Produs::class)->find($id);

        if (!$produs) {
            throw $this->createNotFoundException('Produsul nu a fost găsit!');
        }

        $entityManager->remove($produs);
        $entityManager->flush();

        $this->addFlash('success', 'Produsul a fost șters cu succes!');
        return $this->redirectToRoute('produse');
    }
}
