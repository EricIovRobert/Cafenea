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
    
        $data = $entityManager->getRepository(Data::class)->findBy([], ['Ziua' => 'ASC']); 
    
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
        // Găsim obiectul `Data` pentru ID-ul dat
        $data = $entityManager->getRepository(Data::class)->find($id);

        // Verificăm dacă data există
        if (!$data) {
            throw $this->createNotFoundException('Data nu a fost găsită!');
        }

        // Obținem toate relațiile `ClientZiua` pentru această dată
        $relations = $data->getClientZiuas();

        // Pregătim lista clienților
        $clients = [];
        foreach ($relations as $relation) {
            $client = $relation->getClient();
            if ($client) {
                $clients[] = [
                    'id' => $client->getId(),
                    'nume' => $client->getNume(),
                    'citire_anterioara' => $client->getCitireAnterioara() ?? 0,
                    'citire_actuala' => $client->getCitireActuala() ?? 0,
                    'probe' => $client->getProbe() ?? 0,
                    'pret' => $client->getPret() ?? 0,
                    'cafea_covim' => $client->getCafeaCovim() ?? 0,
                    'cafea_lavazza' => $client->getCafeaLavazza() ?? 0,
                    'zahar' => $client->getZahar() ?? 0,
                    'lapte' => $client->getLapte() ?? 0,
                    'ceai' => $client->getCeai() ?? 0,
                    'solubil' => $client->getSolubil() ?? 0,
                    'pahare_plastic' => $client->getPaharePlastic() ?? 0,
                    'pahare_carton' => $client->getPahareCarton() ?? 0,
                    'palete' => $client->getPalete() ?? 0,
                ];
            }
        }

        // Renderizăm template-ul
        return $this->render('clients/clients.html.twig', [
            'clients' => $clients,
            'date_id' => $id,
            'date_formatted' => $data->getZiua()->format('d.m.Y'),
        ]);
    }

    #[Route('/data/{id}/add-client', name: 'data_add_client')]
public function addClient(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    
}

}


