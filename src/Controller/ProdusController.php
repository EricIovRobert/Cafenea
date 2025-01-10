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
use App\Entity\Operatii;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
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


    #[Route('/produse/operatii/{id}', name: 'produse_operatii')]
    public function operatii(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Găsește produsul
        $produs = $entityManager->getRepository(Produs::class)->find($id);
    
        if (!$produs) {
            throw $this->createNotFoundException('Produsul nu a fost găsit!');
        }
    
        // Creează o nouă operație
        $operatie = new Operatii();
    
        $form = $this->createFormBuilder($operatie)
            ->add('Data', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Data operației',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nr', NumberType::class, [
                'label' => 'Valoare operație (pozitiv pentru adăugare, negativ pentru scădere)',
                'attr' => ['class' => 'form-control'],
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Obține valoarea operației din formular
            $valoare = $form->get('nr')->getData();
    
            // Calculează noul stoc
            $stoc_nou = $produs->getStoc() + $valoare;
    
            // Actualizează stocul produsului
            $produs->setStoc($stoc_nou);
    
            // Completează informațiile operației
            $operatie->setData($form->get('Data')->getData());
            $operatie->setNr($valoare); // Setează valoarea operației
            $operatie->setStocAct($stoc_nou); // Setează stocul după operație
            $operatie->addProd($produs);
    
            // Persistă operația
            $entityManager->persist($operatie);
            $entityManager->flush();
    
            $this->addFlash('success', 'Operația a fost adăugată cu succes!');
            return $this->redirectToRoute('produse');
        }
    
        return $this->render('produse/operatii.html.twig', [
            'form' => $form->createView(),
            'produs' => $produs,
        ]);
    }
    #[Route('/produse/pdf', name: 'produse_pdf')]
public function produseToPdf(Request $request, EntityManagerInterface $entityManager): Response
{
    // Formular pentru selectarea datei
    $form = $this->createFormBuilder()
        ->add('data', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Selectează data',
            'attr' => ['class' => 'form-control'],
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $selectedDate = $form->get('data')->getData();

        // Obține toate produsele
        $produse = $entityManager->getRepository(Produs::class)->findAll();

        // Pregătim lista de produse și stocurile lor
        $dataProduse = [];
        foreach ($produse as $produs) {
            // Găsim operațiile pentru produs și data selectată
            $operatii = $entityManager->getRepository(Operatii::class)
                ->createQueryBuilder('o')
                ->andWhere('o.Data = :data')
                ->andWhere(':produs MEMBER OF o.prod')
                ->setParameter('data', $selectedDate)
                ->setParameter('produs', $produs)
                ->getQuery()
                ->getResult();
        
            // Verificăm dacă există operații
            if (!empty($operatii)) {
                // Preluăm ultima valoare a `stoc_act`
                $stocAct = end($operatii)->getStocAct();
            } else {
                // Dacă nu există operații, folosim valoarea stocului produsului
                $stocAct = $produs->getStoc();
            }
        
            $dataProduse[] = [
                'nume' => $produs->getNume(),
                'stoc_act' => $stocAct,
            ];
        }
        

        // Traducerea zilelor săptămânii
        $zileTraduse = [
            'Monday'    => 'Luni',
            'Tuesday'   => 'Marți',
            'Wednesday' => 'Miercuri',
            'Thursday'  => 'Joi',
            'Friday'    => 'Vineri',
            'Saturday'  => 'Sâmbătă',
            'Sunday'    => 'Duminică',
        ];

        $englishDay = $selectedDate->format('l'); // Ex: "Monday"
        $translatedDay = $zileTraduse[$englishDay] ?? $englishDay;

        // Formatează data cu ziua săptămânii
        $dateFormatted = $selectedDate->format('d.m.Y') . ' (' . $translatedDay . ')';

        // Creează conținutul HTML pentru PDF
        $html = $this->renderView('produse/pdf.html.twig', [
            'produse' => $dataProduse,
            'date_formatted' => $dateFormatted,
        ]);

        // Configurează DomPDF
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Returnăm PDF-ul ca răspuns
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="produse.pdf"',
        ]);
    }

    return $this->render('produse/select_date.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/produse/print', name: 'produse_print')]
public function printProduse(EntityManagerInterface $entityManager): Response
{
    // Obține toate produsele
    $produse = $entityManager->getRepository(Produs::class)->findAll();

    // Obține data și ziua curentă
    $now = new \DateTime();
    $zileTraduse = [
        'Monday'    => 'Luni',
        'Tuesday'   => 'Marți',
        'Wednesday' => 'Miercuri',
        'Thursday'  => 'Joi',
        'Friday'    => 'Vineri',
        'Saturday'  => 'Sâmbătă',
        'Sunday'    => 'Duminică',
    ];
    $currentDay = $zileTraduse[$now->format('l')] ?? $now->format('l');

    // Creează conținutul HTML pentru PDF
    $html = $this->renderView('produse/print.html.twig', [
        'produse' => $produse,
        'current_date' => $now->format('d.m.Y'),
        'current_day' => $currentDay,
    ]);

    // Configurează DomPDF
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->loadHtml($html);
    $dompdf->render();

    // Returnăm PDF-ul ca răspuns
    return new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="produse.pdf"',
    ]);
}



}
