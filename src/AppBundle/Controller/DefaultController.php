<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @Method("GET")
     */
    public function indexAction($max=4)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([],['id'=>'DESC'],$max);

        return $this->render('default/index.html.twig', ['articles' => $articles]);
    }
}
