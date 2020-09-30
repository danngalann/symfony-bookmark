<?php

namespace App\Controller;

use App\Form\BuscadorType;
use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
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
                    "busqueda" => $busqueda
                ]
            ]);
        }

        return $this->render('common/_busqueda.html.twig', [
            'formulario_busqueda' => $formularioBusqueda->createView(),
            'page' => $page,
            'per_page_elements' => self::PER_PAGE_ELEMENTS,
        ]);
    }

    /**
     * @Route("/editar-favorito/", name="app_edit_favorito")
     */
    public function editFavorito(
        MarcadorRepository $marcadorRepository,
        Request $request
    ) {
        if ($request->isXmlHttpRequest()) {
            $actualizado = true;
            $idMarcador = $request->get('id');
            $entityManager = $this->getDoctrine()->getManager();
            $marcador = $marcadorRepository->findOneById($idMarcador);
            $marcador->setFavorito(!$marcador->getFavorito());

            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $actualizado = false;
            }

            return $this->json([
                'favorito' => $marcador->getFavorito(),
                'actualizado' => $actualizado,
            ]);
        }

        throw $this->createNotFoundException();
    }

    /**
     * @Route(
     *  "/favoritos/{page}",
     *  name="app_favoritos",
     *  defaults= {
     *      "page": 1
     *  },
     *  requirements={
     *      "page"="\d+"
     *  }
     * )
     */
    public function favoritos(int $page, MarcadorRepository $marcadorRepository)
    {
        $marcadores = $marcadorRepository->findByFavorites(
            $page,
            self::PER_PAGE_ELEMENTS
        );

        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
            'page' => $page,
            'per_page_elements' => self::PER_PAGE_ELEMENTS,
        ]);
    }

    /**
     * @Route(
     *  "/{categoria}/{page}",
     *  name="app_index",
     *  defaults= {
     *      "categoria": "todas",
     *      "page": 1
     *  },
     *  requirements={
     *      "page"="\d+"
     *  }
     * )
     */
    public function index(
        string $categoria,
        int $page,
        CategoriaRepository $categoriaRepository,
        MarcadorRepository $marcadorRepository
    ) {
        $perPageElements = self::PER_PAGE_ELEMENTS;
        $categoria = (int) $categoria > 0 ? (int) $categoria : $categoria; // Check if categoria really is pagination

        if (is_int($categoria)) {
            $page = $categoria;
            $categoria = 'todas';
        }

        if ($categoria && 'todas' != $categoria) {
            if (!$categoriaRepository->findByNombre($categoria)) {
                throw $this->createNotFoundException(
                    'La categoria ' . $categoria . ' no existe'
                );
            }

            $marcadores = $marcadorRepository->findByCategoriaName(
                $categoria,
                $page,
                $perPageElements
            );
        } else {
            $marcadores = $marcadorRepository->findEverything(
                $page,
                $perPageElements
            );
        }

        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
            'page' => $page,
            'route_params' => [
                'categoria' => $categoria,
            ],
            'per_page_elements' => $perPageElements,
        ]);
    }
}
