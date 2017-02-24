<?php
namespace XpageBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\DomCrawler\Crawler;
use XpageBundle\Utils\UtilityClass;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class NakedScrapeCommand extends ContainerAwareCommand
{
    private $output;
    private $domainToScrape;
    private $pagesScraped;
    private $pagesToScrape = [];
    private $dataScraped = [];
    private $limitPagesScraped;

    protected function configure()
    {
        $this
            ->setName('scrape:naked')
            ->setDescription('Naked Scraper')
            ->addArgument(
                'limit',
                InputArgument::REQUIRED,
                'How Many Videos to Scrape?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $data = [];
        $this->limitPagesScraped = $input->getArgument('limit');
        $this->domainToScrape = 'http://m.naked.com';
        $page = '/index.php?s=video.play';
        $url = $this->domainToScrape . $page;
        //$url = 'http://m.naked.com/index.php?s=video.play&videoId=1044803';

        $this->startScrape($url);
    }

    protected function startScrape($url = null)
    {
        if ($url == null) {
            $url = $this->domainToScrape . $this->pagesToScrape[0];
            unset($this->pagesToScrape[0]);
            $this->pagesToScrape = array_values($this->pagesToScrape);
        }

        $doc = UtilityClass::curl($url);
        $crawler = new Crawler($doc);

        $data['defaultImage'] = $crawler->filter('.trailer-img img')->attr('src');

        if ($this->checkIfPageExists($data['defaultImage'])) {
            array_push($this->dataScraped, $this->scrapeData($crawler));
        }
        $this->pagesToScrape = array_merge($this->pagesToScrape, $this->getPagesToScrape($crawler));
        $this->pagesScraped++;

        if ($this->pagesScraped <= $this->limitPagesScraped) {
            $this->startScrape();
        } else {
            print_r($this->limitPagesScraped);
            print_r($this->pagesScraped);
            print_r($this->pagesToScrape);
            print_r($this->dataScraped);

            $fs = new Filesystem();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

            foreach ($this->dataScraped as $data) {

                // download thumbs
                foreach ($data['thumbs'] as $thumbs) {
                    $sourceSplit = explode('/', $thumbs);

                    $mediaId = $sourceSplit[5];
                    $fileName = str_replace("200x150","320x240",$sourceSplit[6]);
                    if (!$fs->exists("web/tmp/" . $mediaId . "/" . $fileName)) {
                        curl_setopt($ch, CURLOPT_URL, $thumbs);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $file = curl_exec($ch);
                        $info = curl_getinfo($ch);

                        $filesize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

                        $this->output->writeln($fileName.'('.$this->sizeConversion($filesize).') Download Time: ' . $info['total_time']);
                        $fs->dumpFile("web/tmp/" . $mediaId . "/" . $fileName, $file);
                    }
                }

                // download video
                if (!$fs->exists("web/tmp/" . $mediaId . "/" . $mediaId . ".mp4")) {
                    $this->output->writeln('Starting Download of '.$data['video']);
                    curl_setopt($ch, CURLOPT_URL, $data['video']);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_PROXY, '199.227.40.31:80');

                    $file = curl_exec($ch);
                    $info = curl_getinfo($ch);

                    $filesize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                    $this->output->writeln($mediaId . ".mp4".'('.$this->sizeConversion($filesize).')  Download Time: ' . $info['total_time']);

                    $fs->dumpFile("web/tmp/" . $mediaId . "/" . $mediaId . ".mp4", $file);
                }

            }
            curl_close($ch);
        }
    }

    protected function getPagesToScrape($crawler)
    {
        $urlsToScrape = $crawler->filter('.video-list a')->each(function (Crawler $node, $i) {
            return $node->attr('href');
        });
        $urlsToScrape = array_unique($urlsToScrape);
        if (($key = array_search('#', $urlsToScrape)) !== false) {
            unset($urlsToScrape[$key]);
        }

        return $urlsToScrape;
    }

    protected function scrapeData($crawler)
    {
        $data['title'] = $crawler->filter('.h2-title')->text();
        $data['thumbs'] = $crawler->filter('.vid-thumb-list img')->each(function (Crawler $node, $i) {
            return $node->attr('src');
        });
        $data['description'] = trim(preg_replace('/\s+/', ' ', $crawler->filter('.para-desc')->text()));
        $data['video'] = $crawler->filter('.trailer-img a')->attr('href');

        return $data;
    }

    protected function checkIfPageExists($imageSrc)
    {
        $array = explode("/", $imageSrc);

        return ($array[count($array) - 1] != 'emptyperformer.gif');
    }

    private function sizeConversion($clen){
        switch ($clen) {
            case $clen < 1024:
                $size = $clen .' B'; break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) .' KiB'; break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MiB'; break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GiB'; break;
        }

        return $size; // return formatted size
    }

}

