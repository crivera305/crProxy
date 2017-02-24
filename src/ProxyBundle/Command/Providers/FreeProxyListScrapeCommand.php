<?php
namespace ProxyBundle\Command\Providers;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\DomCrawler\Crawler;
use XpageBundle\Utils\UtilityClass;
use ProxyBundle\Entity\Proxies;

class FreeProxyListScrapeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('scrape:freeproxylist')
            ->setDescription('Free Proxy List Scraper');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $date = new \DateTime();

        $url = 'http://free-proxy-list.net';
        $countSuccess = 0;

        $response = UtilityClass::curl($url);
        $crawler = new Crawler($response);
        $proxyRows = $crawler->filter('#proxylisttable tbody tr');

        foreach($proxyRows as $row){
            $rowHTML = $this->get_inner_html($row);
            $rowCrawler = new Crawler($rowHTML);
            $columns = $rowCrawler->filter('td');

            $prox['ip'] = $columns->getNode(0)->nodeValue;
            $prox['port'] = $columns->getNode(1)->nodeValue;
            $prox['country_code'] = $columns->getNode(2)->nodeValue;
            $prox['ssl'] = ($columns->getNode(6)->nodeValue=='yes')?'true':'false';

            $prox['latency'] = 0;
            $prox['type'] = ($prox['ssl']=='true')?'HTTP,HTTPS':"HTTP";


            $proxy = new Proxies();
            $proxy->setIp($prox['ip']);
            $proxy->setPort($prox['port']);
            $proxy->setType($prox['type']);
            $proxy->setIsSsl($prox['ssl']);
            $proxy->setCheckTimestamp('');
            $proxy->setCountryCode($prox['country_code']);
            $proxy->setLatency($prox['latency']);
            $proxy->setReliability('0');
            $proxy->setIsOnline('0');
            $proxy->setDateAdded($date);
            $proxy->setSource($url);

            try {
                $em->persist($proxy);
                $em->flush();
                $countSuccess++;
            } catch(\Exception $e){
                $managerRegistry = $this->getContainer()->get('doctrine');
                $em = $managerRegistry->getManager();
                $managerRegistry->resetManager();
            }
        }
        $output->writeln('    Found: ' . count($proxyRows).', SUCCESS Insert: '.$countSuccess);

    }
    private function get_inner_html( $node ) {
        $innerHTML= '';
        $children = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML( $child );
        }

        return $innerHTML;
    }

}


