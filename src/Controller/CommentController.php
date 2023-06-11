<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment', name: 'comment_')]
class CommentController extends AbstractController
{
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        if ($this->getUser() !== $comment->getAuthor() && !$this->isGranted('ROLE_ADMIN')) {

            throw $this->createAccessDeniedException('Tu n\'es pas autorisé à éditer les commentaires');
        }
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);

            $this->addFlash('danger', 'Votre commentaire a bien été supprimé ! ');
        }
        return $this->redirectToRoute('app_index');
    }
}