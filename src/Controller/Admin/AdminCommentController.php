<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constants\SecurityConstants;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/comments')]
class AdminCommentController extends AbstractCrudController
{
    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SecurityConstants $securityConstants
    ) {
    }

    #[Route('/', name: 'app_admin_comment_index')]
    public function index(): Response
    {
        return $this->renderIndex('admin/comment/index.html.twig', $this->commentRepository->findAllOrderedByDate());
    }

    #[Route('/{id}/delete', name: 'app_admin_comment_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Comment $comment): Response
    {
        return $this->handleDelete(
            $request,
            $this->entityManager,
            $comment,
            'delete',
            'Comment deleted successfully!',
            'app_admin_comment_index'
        );
    }

    protected function getResourceName(): string
    {
        return 'comments';
    }
}
