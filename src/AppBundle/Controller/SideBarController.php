<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SideBarController extends Controller
{

    public function recentArticlesAction($max)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([],['id'=>'DESC'],$max);

        return $this->render('sidebar.html.twig', array('articles' => $articles));
    }

    public function categoryViewAction()
    {
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article');

        $qb = $repo->createQueryBuilder('a');
        $qb->select('a.category');
        $qb->distinct(true);
        $query =$qb->getQuery();
         $categories =$query->getArrayResult();
        return $this->render(':Sidebar:category.html.twig', ['categories' => $categories]);
    }
}
