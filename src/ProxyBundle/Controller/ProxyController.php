<?php
namespace ProxyBundle\Controller;

use ProxyBundle\Utils\ProxyClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProxyController extends Controller
{
    /**
     * @Route("/requestByProxy", name="proxy-request")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function proxyAction(Request $request)
    {
        $url = $request->request->get('url');
        $proxy = $request->request->get('proxy');

        $proxyResponse = ProxyClass::curl($url);

        return $this->render('proxy/browse_now.html.twig', array(
            'url' => $url,
            'response' => $proxyResponse
        ));
    }
}
