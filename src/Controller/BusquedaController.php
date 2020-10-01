<?php

namespace App\Controller;

use App\Form\BuscadorType;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BusquedaController extends AbstractController
{

    public const PER_PAGE_ELEMENTS = 5;

    /**
     * @Route(
     *  "/search/{busqueda}/{page}",
     *  name="app_busqueda",
     *  defaults={
     *      "busqueda": "",
     *      "page": 1
     *  },
     *  requirements={
     *      "page"="\d+"
     *  }
     * )
     */
    public function search(
        string $busqueda,
        int $page,
        MarcadorRepository $marcadorRepository,
        Request $request
    ) {
        $formularioBusqueda = $this->createForm(BuscadorType::class);
        $formularioBusqueda->handleRequest($request);
        $marcadores = [];

        if (
            $formularioBusqueda->isSubmitted() &&
            $formularioBusqueda->isValid()
        ) {
            $busqueda = $formularioBusqueda->get('busqueda')->getData();
        }

        if (!empty($busqueda)) {
            $marcadores = $marcadorRepository->findByName(
                $busqueda,
                $page,
                self::PER_PAGE_ELEMENTS
            );
        }

        if (!empty($busqueda) || $formularioBusqueda->isSubmitted()) {
            return $this->render('index/index.html.twig', [
                'marcadores' => $marcadores,
                'formulario_busqueda' => $formularioBusqueda->createView(),
                'page' => $page,
                'per_page_elements' => self::PER_PAGE_ELEMENTS,
                'route_params' => [
                    'busqueda' => $busqueda,
                ],
            ]);
        }

        return $this->render('busqueda/_busqueda.html.twig', [
            'formulario_busqueda' => $formularioBusqueda->createView(),
            'page' => $page,
            'per_page_elements' => self::PER_PAGE_ELEMENTS,
        ]);
    }
}
