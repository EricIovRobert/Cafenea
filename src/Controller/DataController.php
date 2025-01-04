<?php

namespace App\Controller;

use App\Entity\Data;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DataController extends AbstractController
{
    #[Route('/data', name: 'data')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        
        $data = $entityManager->getRepository(Data::class)->findAll();

       
        $zileTraduse = [
            'Monday'    => 'Luni',
            'Tuesday'   => 'Marți',
            'Wednesday' => 'Miercuri',
            'Thursday'  => 'Joi',
            'Friday'    => 'Vineri',
            'Saturday'  => 'Sâmbătă',
            'Sunday'    => 'Duminică',
        ];

      
        foreach ($data as $entry) {
            if ($entry->getZiua()) {
                $englishDay = $entry->getZiua()->format('l'); 
                $translatedDay = $zileTraduse[$englishDay] ?? $englishDay; 
                $entry->formattedDate = $translatedDay . ', ' . $entry->getZiua()->format('d.m.Y'); 
            }
        }

        return $this->render('data/data.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/data/add', name: 'data_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = new Data();

        $form = $this->createFormBuilder($data)
            ->add('Ziua', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Selectează o dată',
                'attr' => ['class' => 'form-control']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('success', 'Data a fost adăugată cu succes!');

            return $this->redirectToRoute('data'); 
        }

        return $this->render('data/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/data/{id}/clients', name: 'data_clients')]
    public function clientsForDate(int $id, EntityManagerInterface $entityManager): Response
    {
        // Găsim relațiile client_ziua pentru data respectivă
        $clientZiuaRepo = $entityManager->getRepository(\App\Entity\ClientZiua::class);
        $clientRepo = $entityManager->getRepository(\App\Entity\Client::class);
        $relations = $clientZiuaRepo->findBy(['data' => $id]); // `data` este cheia corectă conform relației
    
        // Găsim clienții asociați
        $clients = [];
        foreach ($relations as $relation) {
            $client = $relation->getClient(); // Folosiți metoda `getClient()` deja definită în entitate
            if ($client) {
                $clients[] = $client;
            }
        }
    
        return $this->render('clients/clients.html.twig', [
            'clients' => $clients,
            'date_id' => $id,
        ]);
    }
    


}
