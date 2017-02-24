<?php

namespace XpageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XpageBundle\Utils\UtilityClass;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig', array(

        ));
    }

    /**
     * @Route("/categories", name="xpage_categories")
     */
    public function categoriesAction()
    {
        return $this->render('default/categories.html.twig', array(
            'categories' => UtilityClass::$categories,
        ));
    }

}
