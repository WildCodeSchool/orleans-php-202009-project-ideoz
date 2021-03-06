<?php

namespace App\Controller;

use App\Entity\Testimony;
use App\Repository\CompanyRepository;
use App\Repository\EcosystemRepository;
use App\Repository\StatusRepository;
use App\Repository\TestimonyRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @param TestimonyRepository $testimonyRepository
     * @param OfferRepository $offerRepository
     * @param EcosystemRepository $ecosystemRepository
     * @param StatusRepository $statusRepository
     * @return Response
     */

    public function index(
        TestimonyRepository $testimonyRepository,
        OfferRepository $offerRepository,
        EcosystemRepository $ecosystemRepository,
        StatusRepository $statusRepository
    ): Response {
        $client = $statusRepository->findOneBy(['name' => 'Client']);
        $partner = $statusRepository->findOneBy(['name' => 'Partenaire']);

        return $this->render('home/index.html.twig', [
            'services' => $offerRepository->findAll(),
            'testimonies' => $testimonyRepository->findBy([], ['id' => 'DESC'], 4),
            'clients' => $ecosystemRepository->findBy(
                ['status' => $client, 'isValidated' => true],
                ['id' => 'ASC'],
                10
            ),
            'partners' => $ecosystemRepository->findBy(
                ['status' => $partner, 'isValidated' => true],
                ['id' => 'ASC'],
                10
            ),
        ]);
    }

    /**
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public function navbarOffer(OfferRepository $offerRepository): Response
    {
        return $this->render('includes/_navbarOffer.html.twig', [
            'services' => $offerRepository->findAll(),
        ]);
    }

    /**
     * @param CompanyRepository $companyRepository
     * @return Response
     */
    public function footerContact(CompanyRepository $companyRepository): Response
    {
        return $this->render('includes/_footerContact.html.twig', [
            'contact' => $companyRepository->findOneBy([]),
        ]);
    }
}
