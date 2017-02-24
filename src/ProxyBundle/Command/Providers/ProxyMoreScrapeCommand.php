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

class ProxyMoreScrapeCommand extends ContainerAwareCommand
{
    private $output;
    private $totalPages;

    protected function configure()
    {
        $this
            ->setName('scrape:proxymore')
            ->setDescription('ProxyMore Scraper');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $url = 'http://www.proxymore.com/proxy-list-1.html';
        $response = UtilityClass::curl($url);
        $crawler = new Crawler($response);

        $pagination = $crawler->filter('#x-page a');
        $paginationArray = explode("-",$this->get_inner_html($pagination->getNode(10)->parentNode));
        $paginationArray = explode(".",$paginationArray[2]);
        $this->totalPages = $paginationArray[0];

        for ($x = 1; $x <= $this->totalPages; $x++) {
            $this->scrapePage($x);
            sleep(1);
        }

    }

    private function scrapePage($page)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $date = new \DateTime();

        $url = 'http://www.proxymore.com/proxy-list-'.$page.'.html';

        $countSuccess = 0;

        $response = UtilityClass::curl($url);
        $crawler = new Crawler($response);

        $proxyRows = $crawler->filter('.x-table .x-tr');

        foreach ($proxyRows as $row) {
            $rowHTML = $this->get_inner_html($row);
            $rowCrawler = new Crawler($rowHTML);
            $columns = $rowCrawler->filter('td');

            $prox['ip'] = $columns->getNode(1)->nodeValue;
            $prox['port'] = $columns->getNode(2)->nodeValue;
            $prox['type'] = strtoupper($columns->getNode(3)->nodeValue);
            $prox['ssl'] = ($prox['type'] == 'HTTPS') ? 'true' : 'false';
            $prox['latency'] = 0;

            $countryCodeHTML = $this->get_inner_html($columns->getNode(5));
            $countryCodeHTMLArray = explode('"', $countryCodeHTML);
            $prox['country_code'] = $countryCodeHTMLArray[3];

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
            } catch (\Exception $e) {
                $managerRegistry = $this->getContainer()->get('doctrine');
                $em = $managerRegistry->getManager();
                $managerRegistry->resetManager();
            }
        }

        $this->output->writeln('    Page: '.$page.'/'.$this->totalPages.'  Found: ' . count($proxyRows) . ', SUCCESS Insert: ' . $countSuccess);
    }

    private function get_inner_html($node)
    {
        $innerHTML = '';
        $children = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML($child);
        }

        return $innerHTML;
    }

}


