<?php

namespace App\Controller;

use App\Entity\Marcador;
use App\Entity\MarcadorEtiqueta;
use App\Form\MarcadorType;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/marcador")
 */
class MarcadorController extends AbstractController
{
    /**
     * @Route("/new", name="marcador_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $marcador = new Marcador();
        $form = $this->createForm(MarcadorType::class, $marcador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $marcador->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($marcador);
            $etiquetas = $form->get('etiquetas')->getData();

            foreach ($etiquetas as $etiqueta) {
                $etiqueta->setUser($user);
                $marcadorEtiqueta = new MarcadorEtiqueta();
                $marcadorEtiqueta->setMarcador($marcador);
                $marcadorEtiqueta->setEtiqueta($etiqueta);
                $entityManager->persist($marcadorEtiqueta);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Marcador añadido correctamente');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('marcador/new.html.twig', [
            'marcador' => $marcador,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="marcador_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Marcador $marcador): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(MarcadorType::class, $marcador);
        $form->handleRequest($request);

        $marcadorEtiquetasActuales = $marcador->getMarcadorEtiquetas();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $etiquetas = $form->get('etiquetas')->getData();

                // Para eliminar etiquetas
                foreach (
                    $marcadorEtiquetasActuales
                    as $marcadorEtiquetaActual
                ) {
                    $eliminar = true;
                    $etiquetaActual = $marcadorEtiquetaActual->getEtiqueta();
                    foreach ($etiquetas as $etiqueta) {
                        if ($etiquetaActual->getId() == $etiqueta->getId()) {
                            $eliminar = false;
                        }
                    }
                    if ($eliminar) {
                        $entityManager->remove($marcadorEtiquetaActual);
                    }
                }

                // Para añadir etiquetas
                foreach ($etiquetas as $etiqueta) {
                    $crear = true;
                    foreach (
                        $marcadorEtiquetasActuales
                        as $marcadorEtiquetaActual
                    ) {
                        if ($etiquetaActual->getId() == $etiqueta->getId()) {
                            $crear = false;
                        }
                    }
                    if ($crear) {
                        $etiqueta->setUser($user);
                        $marcadorEtiqueta = new MarcadorEtiqueta();
                        $marcadorEtiqueta->setMarcador($marcador);
                        $marcadorEtiqueta->setEtiqueta($etiqueta);
                        $entityManager->persist($marcadorEtiqueta);
                    }
                }

                $entityManager->flush();
                $this->addFlash('success', 'Marcador editado correctamente');
                return $this->redirectToRoute('app_index');
            }
        } else {
            $etiquetas = [];
            foreach ($marcadorEtiquetasActuales as $marcadorEtiqueta) {
                $etiquetas[] = $marcadorEtiqueta->getEtiqueta();
            }
            $form->get('etiquetas')->setData($etiquetas);
        }

        return $this->render('marcador/edit.html.twig', [
            'marcador' => $marcador,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="marcador_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Marcador $marcador): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $marcador->getId(),
                $request->request->get('_token')
            )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($marcador);
            $entityManager->flush();
            $this->addFlash('success', 'Marcador eliminado correctamente');
        }

        return $this->redirectToRoute('app_index');
    }
}
