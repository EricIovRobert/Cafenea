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
                    'ciocolata' => $client->getCiocolata() ?? 0,
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
        // Găsim obiectul `Data` pentru ID-ul dat
        $data = $entityManager->getRepository(Data::class)->find($id);
    
        // Verificăm dacă data există
        if (!$data) {
            throw $this->createNotFoundException('Data nu a fost găsită!');
        }
    
        // Creăm un nou client
        $client = new \App\Entity\Client();
        $clientZiua = new \App\Entity\ClientZiua();
        $clientZiua->setData($data);
        $clientZiua->setClient($client);
    
        // Obținem lista numelor clienților existenți
        $clientNames = $entityManager->getRepository(\App\Entity\Client::class)
            ->createQueryBuilder('c')
            ->select('c.Nume')
            ->distinct()
            ->getQuery()
            ->getResult();
    
        $clientNameChoices = array_map(function ($c) {
            return $c['Nume'];
        }, $clientNames);
    
        // Construim formularul
        $form = $this->createFormBuilder($client)
            ->add('Nume', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'label' => 'Nume Client',
                'attr' => [
                    'class' => 'form-control combobox',
                    'list' => 'client-names', // Asociază lista cu id-ul datalist
                    'placeholder' => 'Selectează sau introdu manual',
                ],
                'required' => true,
            ])
            ->add('Citire_anterioara', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Citire Anterioară',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Citire_actuala', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Citire Actuală',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('Probe', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Probe',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Pret', \Symfony\Component\Form\Extension\Core\Type\NumberType::class, [
                'label' => 'Preț',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('CafeaCovim', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Cafea Covim',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('CafeaLavazza', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Cafea Lavazza',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Zahar', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Zahar',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Lapte', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Lapte',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Ciocolata', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Ciocolata',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Ceai', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Ceai',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Solubil', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Solubil',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Pahare_plastic', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Pahare Plastic',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Pahare_carton', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Pahare Carton',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('Palete', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Palete',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, [
                'label' => 'Adaugă Client',
                'attr' => ['class' => 'btn btn-success mt-3'],
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $clientNume = $client->getNume();
    
            // Verificăm dacă numele există deja
            $existingClient = $entityManager->getRepository(\App\Entity\Client::class)
                ->findOneBy(['Nume' => $clientNume], ['id' => 'DESC']);
    
            if ($existingClient) {
                // Autocompletăm `citire_anterioara` cu ultima `citire_actuala`
                $client->setCitireAnterioara($existingClient->getCitireActuala());
            }
    
            $entityManager->persist($client);
            $entityManager->persist($clientZiua);
            $entityManager->flush();
    
            $this->addFlash('success', 'Clientul a fost adăugat cu succes!');
    
            return $this->redirectToRoute('data_clients', ['id' => $id]);
        }
    
        return $this->render('clients/add_client.html.twig', [
            'form' => $form->createView(),
            'date_formatted' => $data->getZiua()->format('d.m.Y'),
            'clientNames' => $clientNameChoices, // Trimitem numele clienților către template
        ]);
    }
    
    

        #[Route('/api/get-last-citire-actuala', name: 'get_last_citire_actuala')]
public function getLastCitireActuala(Request $request, EntityManagerInterface $entityManager): Response
{
    $clientName = $request->query->get('client');
    $client = $entityManager->getRepository(\App\Entity\Client::class)
        ->findOneBy(['Nume' => $clientName], ['id' => 'DESC']);

    return $this->json([
        'citire_actuala' => $client ? $client->getCitireActuala() : null,
    ]);
}


}


