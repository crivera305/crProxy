<?php
namespace ProxyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use ProxyBundle\Entity\Proxies;
use ProxyBundle\Entity\ProxyHistory;
use XpageBundle\Utils\UtilityClass;


class CheckProxyStatusCommand extends ContainerAwareCommand
{
    private $doctrine;
    private $output;
    private $proxyList = [];
    private $publicIP = [];

    protected function configure()
    {
        $this
            ->setName('proxy:check')
            ->setDescription('Checks the status of the proxies saved in the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $this->output->writeln('');
        $this->output->writeln('proxy:check Started');
        $this->output->writeln('');

        $this->doctrine = $this->getContainer()->get('doctrine');

        $publicIP = $this->getPublicIP();
        $this->setPublicIP($publicIP);

        $this->proxyList = $this->getProxiesToCheck();

        $this->checkProxies();
    }

    private function getPublicIP()
    {
        $url = 'http://dynupdate.no-ip.com/ip.php';

        return UtilityClass::curl($url);
    }

    private function setPublicIP($ip)
    {
        $this->publicIP = $ip;
        return $this;
    }

    private function getProxiesToCheck()
    {
        $em = $this->doctrine->getManager();

        $date = new \DateTime();
        $date->modify('-10 minutes');

        $queryProxies = $em->createQuery('SELECT p FROM  ProxyBundle\Entity\Proxies p WHERE p.dateChecked < :date OR p.dateChecked IS NULL')
            ->setParameter('date', $date);

        $proxies = $queryProxies->getResult();

        return $proxies;
    }

    private function checkProxies()
    {

        foreach ($this->proxyList as $proxy) {
            $this->output->writeln('Testing Proxy ' . $proxy->getIp() . ":" . $proxy->getPort());
            $results = $this->validateProxy($proxy->getIp(), $proxy->getPort());

            $this->saveProxyResults($proxy, $results);
        }
    }

    private function saveProxyResults($proxy, $results)
    {
        $em = $this->doctrine->getManager();

        $proxy->setDateChecked(new \DateTime());

        $proxyHistory = new ProxyHistory();
        $proxyHistory->setDateChecked(new \DateTime());
        $proxyHistory->setIp($proxy->getIp());

        if ($results['success']) {
            $proxy->setIsOnline('1');
            $proxy->setLatency($results['speed']);
        } else {
            $proxy->setIsOnline('0');
            $proxy->setLatency('0');
        }
        $proxyHistory->setIsOnline($proxy->getIsOnline());
        $proxyHistory->setLatency($proxy->getLatency());

        $em->persist($proxy);
        $em->persist($proxyHistory);
        $em->flush();
    }

    private function validateProxy($ip, $port)
    {
        $response = [];
        $url = 'http://dynupdate.no-ip.com/ip.php';
        $proxy = $ip . ':' . $port;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $proxyRequest = curl_exec($ch);

        if (!curl_errno($ch)) {
            $this->output->writeln('   Proxy Connection SUCCESS ' . $ip . ":" . $port);
            if (!filter_var($proxyRequest, FILTER_VALIDATE_IP)) {
                $this->output->writeln('   Proxy FAILURE ' . $proxyRequest);
                $response['success'] = false;
            } else {
                $info = curl_getinfo($ch);
                // proxy is connected & working time to document performance
                $this->output->writeln('   Proxy SPEED ' . $info['total_time']);

                $response['success'] = true;
                $response['speed'] = $info['total_time'];
            }
        } else {
            $this->output->writeln('   Proxy Connection FAILED ' . $ip . ":" . $port);
            $response['success'] = false;

        }
        curl_close($ch);
        return $response;
    }

}