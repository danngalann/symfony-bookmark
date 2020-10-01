<?php

namespace App\Controller;

use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FavoritosController extends AbstractController
{

    public const PER_PAGE_ELEMENTS = 5;

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
}
