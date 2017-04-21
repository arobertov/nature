<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    /**
     * @Route("comment/create_comment/{id}",name="create_comment")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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
            return $this->redirectToRoute('article_view',array('id'=>$id));
        }

        return $this->render(':article:comment_form.html.twig',array('id'=>$id,'form' => $form->createView()));
    }

    /**
     * @Route("/comment/edit_comment/{id}/{articleId}",name="edit_comment")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCommentAction($id,$articleId,Request $request)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);

        if($comment===null)
        {
            return $this->redirectToRoute('article_view', array('id' => $articleId));
        }

        $currentUser = $this->getUser();

        if (!$currentUser->isAuthorComment($comment)&&!$currentUser->isAdmin())
        {
            return $this->redirectToRoute('article_view', array('id' => $articleId));
        }

        $form= $this->createForm(new CommentType(),$comment);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your comment edit successfully!'
            );
            return $this->redirectToRoute('article_view', array('id' => $articleId));
        }
     return $this->render('comments/edit_comment.html.twig',array('id'=>$id,'articleId'=>$articleId,'form'=>$form->createView()));
    }


    public function viewCommentAction($id)
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(array('articleId'=>$id));
        return $this->render('article/comment.html.twig',array('comments'=>$comments));
    }

    public function renderFormAction($id,Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);
        $comment->setArticleId($id);
        return $this->render(':article:comment_form.html.twig',array('id'=>$id,'form' => $form->createView()));
    }
}
