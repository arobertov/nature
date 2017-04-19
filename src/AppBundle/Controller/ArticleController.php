<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @Route("/article/create",name="article_create")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('article/create.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @param $id
     * @Route("/article/{id}",name="article_view")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewArticle($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('article/view.htm.twig',['article'=>$article]);
    }

    /**
     * @param $author
     * @Route("/article/author_preview/{authorId}", name="author_preview")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function author_view_Action($authorId)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(array('authorId'=>$authorId));

        if (!$articles) {
            throw $this->createNotFoundException(
                'No product found for author: '.$authorId
            );
        }

        return $this->render('article/author_preview.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/article/edit/{id}",name="article_edit")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editArticle($id,Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if($article===null)
        {
            return $this->redirectToRoute("homepage");
        }

        $currentUser = $this->getUser();

        if (!$currentUser->isAuthor($article)&&!$currentUser->isAdmin())
        {
            return $this->redirectToRoute("homepage");
        }
        $form= $this->createForm(new ArticleType(),$article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your article edit successfully!'
            );

            return $this->redirectToRoute('article_view', array('id'=>$article->getId()));
        }

        return $this->render('article/edit.html.twig',
            array('article'=>$article,
                'form'=> $form->createView()));
    }

    /**
     * @Route("/article/delete/{id}",name="article_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function deleteArticle($id, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if($article===null)
        {
            return $this->redirectToRoute("homepage");
        }
        $form= $this->createForm(new ArticleType(),$article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            return $this->redirectToRoute("homepage");
        }
        return $this->render('article/delete.html.twig',
            array('article'=>$article,
                'form'=> $form->createView()) );
    }

    }
