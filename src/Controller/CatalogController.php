<?php

namespace App\Controller;


use App\Entity\BasketPosition;
use App\Entity\Catalog;
use App\Form\BasketPositionFormType;
use App\Repository\BasketPositionRepository;
use App\Repository\CatalogRepository;
use App\Service\BasketCalcInterface;
use App\Service\BasketCalculator;
use App\Service\CustomMakerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CatalogController extends AbstractController
{
    private $twig;
    private $entityManager;


    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'homepage')]
    public function index(Request $request,CatalogRepository $catalogRepository, BasketPositionRepository $basketPosition, BasketCalcInterface $basketCalculator ): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $sessionId = $request->getSession()->getId();
        $paginator = $catalogRepository->getCatalogPaginator($offset);

        return new Response($this->twig->render('catalog/index.html.twig', [
            'catalogs' => $paginator,
            'session' => $sessionId ,
//            'totalPrice' => $basketCalculator->getBasketPrice($sessionId),
//            'totalQuantity' =>  $basketCalculator->getBasketQuantity($sessionId),
            'previous' => $offset - CatalogRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CatalogRepository::PAGINATOR_PER_PAGE),
        ]));
    }

    #[Route('/pizza/{id}', name: 'pizza')]
    public function show(Request $request, Catalog $catalog, BasketCalcInterface $basketCalculator): Response
    {
        $sessionId = $request->getSession()->getId();
        $basketPosition = new BasketPosition();
        $form = $this->createForm(BasketPositionFormType::class, $basketPosition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $basketPosition->setSessionID($sessionId);
            $basketPosition->setTitle($catalog->getTitle());
            $basketPosition->setPrice($catalog->getPrice());
            $basketPosition->setCatalog($catalog);
            $ingridients = $catalog->getIngr();
            foreach ($ingridients  as $ingridient) {
                $basketPosition->addIngr($ingridient);
            }
            $this->entityManager->persist($basketPosition);
            $this->entityManager->flush();

            return $this->redirectToRoute('homepage');
        }


        return new Response($this->twig->render('catalog/show.html.twig', [
            'catalog_unit' => $catalog,
            'session' => $sessionId,
//            'totalPrice' => $basketCalculator->getBasketPrice($sessionId),
//            'totalQuantity' =>  $basketCalculator->getBasketQuantity($sessionId),
            'ingridients' => $catalog->getIngr(),
            'add_basket_form' => $form->createView(),
        ]));
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {

        return new Response($this->twig->render('about.html.twig'));

    }


}
