<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{

    public function createCommentAction($id,Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);
        $comment->setArticleId($id);
        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setAuthor($this->getUser());

            $user= $comment->getAuthor();
            if($user==null)
                $comment->setAuthor('Anonymous');
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $this->addFlash(
                'notice',
                'Your comment added successfully!'
            );
            return $this->redirectToRoute('article_view');
        }
        return $this->render(':article:comment_form.html.twig',array('id'=>$id,'form' => $form->createView()));
    }


    public function viewCommentAction($id)
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        return $this->render('article/comment.html.twig',array('comments'=>$comments));
    }
}
