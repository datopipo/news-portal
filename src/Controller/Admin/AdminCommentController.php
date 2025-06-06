<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCommentController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CommentRepository $commentRepository
    ) {
    }

    public function index(): Response
    {
        $comments = $this->commentRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('admin/comment/index.html.twig', ['comments' => $comments]);
    }

    public function show(int $id): Response
    {
        $comment = $this->commentRepository->find($id);
        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }
        return $this->render('admin/comment/show.html.twig', ['comment' => $comment]);
    }

    public function delete(Request $request, int $id): Response
    {
        $comment = $this->commentRepository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
            $this->addFlash('success', 'Comment deleted successfully.');
        }

        return $this->redirectToRoute('app_admin_comment_index');
    }

    public function newsByComments(int $id): Response
    {
        $comments = $this->commentRepository->findBy(['news' => $id], ['createdAt' => 'DESC']);
        return $this->render('admin/comment/news_comments.html.twig', [
            'comments' => $comments,
            'newsId' => $id
        ]);
    }
}
