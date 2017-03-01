<?php
namespace ProxyBundle\Command\Providers;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use ProxyBundle\Entity\Proxies;


class XroxyScrapeCommand extends ContainerAwareCommand
{
    private $proxyList = [];

    protected function configure()
    {
        $this
            ->setName('scrape:xroxy')
            ->setDescription('Xroxy Scraper');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $reader = $this->getContainer()->get('debril.reader');

        $date = new \DateTime();
        $url = 'http://www.xroxy.com/proxyrss.xml';

        $feed = $reader->getFeedContent($url, $date);
        $items = $feed->getItems();



        $countSuccess = 0;

        foreach ( $items as $item ) {
            $proxyList = $item->getAdditional();

            if(isset($proxyList['prx'])) {
                foreach ($proxyList['prx']->proxy as $prox) {

                    $prox = (array)$prox;

                    $proxy = new Proxies();
                    $proxy->setIp($prox['ip']);
                    $proxy->setPort($prox['port']);
                    $proxy->setType($prox['type']);
                    $proxy->setIsSsl($prox['ssl']);
                    $proxy->setCheckTimestamp($prox['check_timestamp']);
                    $proxy->setCountryCode($prox['country_code']);
                    $proxy->setLatency($prox['latency']);
                    $proxy->setReliability($prox['reliability']);
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
                    array_push($this->proxyList, $prox);
                }
            }
        }
        $output->writeln('    Found: ' . count($this->proxyList).', SUCCESS Insert: '.$countSuccess);

    }


}