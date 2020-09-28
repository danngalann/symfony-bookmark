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

    /**
     * @Route("/edit/{id}", name="app_edit_categoria")
     */
    public function edit(
        // Symfony hará la union entre el ID que le llega por la URL y categoría, intentando hacer un find automáticamente.
        // Esto significa que $categoria ya vendrá de la base de datos.
        Categoria $categoria,
        EntityManagerInterface $entityManager,
        Request $request
    ) {
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
                $this->addFlash('success', 'Categoria editada correctamente');
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
        return $this->render('categoria/edit.html.twig', [
            'categoria' => $categoria,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_delete_categoria")
     */
    public function delete(
        // Symfony hará la union entre el ID que le llega por la URL y categoría, intentando hacer un find automáticamente.
        // Esto significa que $categoria ya vendrá de la base de datos.
        Categoria $categoria,
        Request $request
    ) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categoria);
        $entityManager->flush();
        $this->addFlash('success', 'Categoría eliminada correctamente');

        return $this->redirectToRoute('app_list_categoria');
    }
}
