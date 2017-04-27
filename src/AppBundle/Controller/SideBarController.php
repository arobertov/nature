<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SideBarController extends Controller
{
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
