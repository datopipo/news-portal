<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseAdminController extends AbstractController
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Find entity by ID or throw 404 exception
     */
    protected function findEntityOr404(string $repository, int $id, string $entityName = 'Entity'): object
    {
        $entity = $this->entityManager->getRepository($repository)->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException($entityName . ' not found');
        }
        
        return $entity;
    }

    /**
     * Handle form submission with flash messages
     */
    protected function handleFormSubmission(
        FormInterface $form, 
        object $entity, 
        string $entityName, 
        string $redirectRoute,
        bool $isNew = true
    ): ?Response {
        if ($form->isSubmitted() && $form->isValid()) {
            if ($isNew) {
                $this->entityManager->persist($entity);
                $action = 'created';
            } else {
                $action = 'updated';
            }
            
            $this->entityManager->flush();
            
            $this->addFlash('success', ucfirst($entityName) . ' ' . $action . ' successfully.');
            return $this->redirectToRoute($redirectRoute);
        }
        
        return null;
    }

    /**
     * Handle entity deletion with CSRF protection
     */
    protected function handleEntityDeletion(
        Request $request,
        object $entity,
        string $entityName,
        string $redirectRoute
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $entity->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $this->addFlash('success', ucfirst($entityName) . ' deleted successfully.');
        }
        
        return $this->redirectToRoute($redirectRoute);
    }
} 