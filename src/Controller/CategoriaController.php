<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categoria")
 */
class CategoriaController extends AbstractController
{
    /**
     * @Route("/listado", name="app_list_categoria")
     */
    public function index(CategoriaRepository $categoriaRepository)
    {
        $categorias = $categoriaRepository->findAll();
        return $this->render('categoria/index.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    /**
     * @Route("/add", name="app_add_categoria")
     */
    public function add(
        CategoriaRepository $categoriaRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ) {
        $categoria = new Categoria();
        if (
            $this->isCsrfTokenValid(
                'categoria',
                $request->request->get('_token')
            )
        ) {
            $nombre = $request->request->get('nombre');
            $color = $request->request->get('color');

            $categoria->setNombre($nombre);
            $categoria->setColor($color);

            if ($nombre && $categoria) {
                $entityManager->persist($categoria);
                $entityManager->flush();
                $this->addFlash('success', 'Categoria creada correctamente');
                return $this->redirectToRoute('app_list_categoria');
            } else {
                if (!$nombre) {
                    $this->addFlash('danger', 'Nombre es obligatorio');
                }
                
                if (!$color) {
                    $this->addFlash('danger', 'Color es obligatorio');
                }
            }
        }
        return $this->render('categoria/add.html.twig', [
            'categoria' => $categoria,
        ]);
    }
}
