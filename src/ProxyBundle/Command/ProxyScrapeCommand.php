<?php
namespace ProxyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class ProxyScrapeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('scrape:proxies')
            ->setDescription('Wrapper to Manage Scrapers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication();

        $proxies[] = 'scrape:xroxy';
        $proxies[] = 'scrape:incloak';
        $proxies[] = 'scrape:usproxy';
        $proxies[] = 'scrape:freeproxylist';
        $proxies[] = 'scrape:sslproxy';
        $proxies[] = 'scrape:proxymore';

        foreach ($proxies as $proxy) {
            $output->writeln('Begin: ' . $proxy);

            $command = new ArrayInput(array('command' => $proxy));
            $app->doRun($command, $output);

            $output->writeln('Completed: ' . $proxy);
        }

    }


}