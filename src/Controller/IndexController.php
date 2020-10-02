<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class IndexController extends AbstractController
{
    public const PER_PAGE_ELEMENTS = 5;

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
     *  },
     *  methods={
     *      "GET"
     *  }
     * )
     */
    public function index(
        string $categoria,
        int $page,
        CategoriaRepository $categoriaRepository,
        MarcadorRepository $marcadorRepository,
        TranslatorInterface $translator,
        Security $security
    ) {

        $user = $security->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $perPageElements = self::PER_PAGE_ELEMENTS;
        $categoria = (int) $categoria > 0 ? (int) $categoria : $categoria; // Check if categoria really is pagination

        if (is_int($categoria)) {
            $page = $categoria;
            $categoria = 'todas';
        }

        if ($categoria && 'todas' != $categoria) {
            if (!$categoriaRepository->findBy([
                    'nombre' => $categoria,
                    'user' => $this->getUser()
                ])) {
                throw $this->createNotFoundException(
                    $translator->trans('La categoria "{categoria}" no existe', [
                        '{cateogoria}' => $categoria
                    ], 'messages')                    
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
