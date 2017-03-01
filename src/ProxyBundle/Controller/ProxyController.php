<?php
namespace ProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use XpageBundle\Utils\UtilityClass;

class ProxyController extends Controller
{
    /**
     * @Route("/", name="proxy-dashboard")
     */
    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT p FROM ProxyBundle\Entity\Proxies p WHERE p.isOnline = 1');
        $onlineProxies = $query->getResult();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT p FROM ProxyBundle\Entity\Proxies p WHERE p.isOnline != 1');
        $offlineProxies = $query->getResult();

        $query = $em->createQuery('SELECT count(p) as countryCount,p.country_code as countryCode FROM ProxyBundle\Entity\Proxies p WHERE p.isOnline != 1 GROUP BY countryCode');
        $offlineCountryProxies = $query->getResult();

        $query = $em->createQuery('SELECT count(p) as countryCount,p.country_code as countryCode FROM ProxyBundle\Entity\Proxies p WHERE p.isOnline = 1 GROUP BY countryCode');
        $onlineCountryProxies = $query->getResult();

        return $this->render('proxy/dashboard.html.twig', array(
            'onlineProxies' => $onlineProxies,
            'offlineProxies' => $offlineProxies,
            'offlineCountryProxies' => $offlineCountryProxies,
            'onlineCountryProxies' => $onlineCountryProxies
        ));
    }

    /**
     * @Route("/proxy/by-country/{country}", name="proxy-by-country")
     */
    public function byCountryAction($country)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT p FROM ProxyBundle\Entity\Proxies p WHERE p.isOnline != 1 AND p.country_code = '".$country."'");
        $offlineProxies = $query->getResult();

        $query = $em->createQuery("SELECT p FROM ProxyBundle\Entity\Proxies p WHERE p.isOnline = 1 AND p.country_code = '".$country."'");
        $onlineProxies = $query->getResult();


        return $this->render('proxy/by_country.html.twig', array(
            'country' => $country,
            'onlineProxies' => $onlineProxies,
            'offlineProxies' => $offlineProxies
        ));
    }

    /**
     * @Route("/proxy/countries", name="proxy-countries")
     */
    public function countriesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT count(p) as countryCount,p.country_code as countryCode FROM ProxyBundle\Entity\Proxies p GROUP BY countryCode');
        $countryProxies = $query->getResult();

        return $this->render('proxy/countries.html.twig', array(
            'countries' => $countryProxies,
        ));
    }

    /**
     * @Route("/proxy/history/{ip}", name="proxy-history")
     */
    public function historyAction($ip)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT p FROM ProxyBundle\Entity\ProxyHistory p WHERE p.ip = '".$ip."'");
        $proxy = $query->getResult();

        return $this->render('proxy/history.html.twig', array(
            'proxies' => $proxy,
        ));
    }

    /**
     * @Route("/proxy/browse", name="proxy-browse")
     */
    public function browseAction()
    {
        return $this->render('proxy/browse.html.twig', array(
        ));
    }

    /**
     * @Route("/proxy/browse/now", name="proxy-browse-now")
     */
    public function browseNowAction(Request $request)
    {
        $url = $request->request->get('url');
        $proxy = $request->request->get('proxy');

        $response = UtilityClass::curlByProxy($url,$proxy);

        return $this->render('proxy/browse_now.html.twig', array(
            'url' =>$url,
            'response' =>$response
        ));
    }


}
