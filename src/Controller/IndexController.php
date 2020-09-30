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
    /**
     * @Route("/search/{busqueda}",
     * name="app_busqueda",
     * defaults={
     *  "busqueda": ""
     * })
     */
    public function search(
        string $busqueda,
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
            $marcadores = $marcadorRepository->findByName($busqueda);
        }

        if (!empty($busqueda) || $formularioBusqueda->isSubmitted()) {
            return $this->render('index/index.html.twig', [
                'marcadores' => $marcadores,
                'formulario_busqueda' => $formularioBusqueda->createView(),
            ]);
        }

        return $this->render('common/_busqueda.html.twig', [
            'formulario_busqueda' => $formularioBusqueda->createView(),
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
     * @Route("/favoritos", name="app_favoritos")
     */
    public function favoritos(MarcadorRepository $marcadorRepository)
    {
        $marcadores = $marcadorRepository->findBy([
            'favorito' => true,
        ]);

        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);
    }

    /**
     * @Route(
     *  "/{categoria}",
     *  name="app_index",
     *  defaults= {
     *      "categoria": ""
     *  })
     */
    public function index(
        string $categoria,
        CategoriaRepository $categoriaRepository,
        MarcadorRepository $marcadorRepository
    ) {
        if (!empty($categoria)) {
            if (!$categoriaRepository->findByNombre($categoria)) {
                throw $this->createNotFoundException(
                    'La categoria ' . $categoria . ' no existe'
                );
            }

            $marcadores = $marcadorRepository->findByCategoriaName($categoria);
        } else {
            $marcadores = $marcadorRepository->findAll();
        }

        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);
    }
}
