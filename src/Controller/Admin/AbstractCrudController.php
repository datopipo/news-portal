<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractCrudController extends AbstractController
{
    protected function renderIndex(string $template, array $items): Response
    {
        return $this->render($template, [
            $this->getResourceName() => $items,
        ]);
    }

    protected function handleNew(
        Request $request,
        EntityManagerInterface $entityManager,
        object $entity,
        string $formClass,
        string $template,
        string $successMessage,
        string $redirectRoute,
        ?callable $beforePersist = null
    ): Response {
        $form = $this->createForm($formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($beforePersist) {
                $beforePersist($form, $entity);
            }
            
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('success', $successMessage);
            return $this->redirectToRoute($redirectRoute);
        }

        return $this->render($template, [
            $this->getResourceName() => $entity,
            'form' => $form->createView(),
        ]);
    }

    protected function handleEdit(
        Request $request,
        EntityManagerInterface $entityManager,
        object $entity,
        string $formClass,
        string $template,
        string $successMessage,
        string $redirectRoute,
        ?callable $beforeFlush = null
    ): Response {
        $form = $this->createForm($formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($beforeFlush) {
                $beforeFlush($form, $entity);
            }
            
            $entityManager->flush();

            $this->addFlash('success', $successMessage);
            return $this->redirectToRoute($redirectRoute);
        }

        return $this->render($template, [
            $this->getResourceName() => $entity,
            'form' => $form->createView(),
        ]);
    }

    protected function handleDelete(
        Request $request,
        EntityManagerInterface $entityManager,
        object $entity,
        string $csrfTokenId,
        string $successMessage,
        string $redirectRoute
    ): Response {
        if ($this->isCsrfTokenValid($csrfTokenId . $entity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($entity);
            $entityManager->flush();
            $this->addFlash('success', $successMessage);
        }

        return $this->redirectToRoute($redirectRoute);
    }

    abstract protected function getResourceName(): string;
} 