<?php

namespace XpageBundle\Utils;

class UtilityClass
{
    static $categories = array(
        '3d Animation',
        'Amateur',
        'Anal',
        'Anime',
        'Arab',
        'Asian',
        'Ass',
        'Babe',
        'Babysitter',
        'BBW',
        'BDSM',
        'Beach',
        'Big Ass',
        'Big Cock',
        'Big Tits',
        'Bikini',
        'Bisexual',
        'Bizarre',
        'Black',
        'Blonde',
        'Blowjob',
        'Bondage',
        'Booty',
        'Brazilian',
        'Bride',
        'Brunette',
        'Camel Toe',
        'Casting',
        'Celebrity',
        'CFNM',
        'Cheerleader',
        'Close Up',
        'College',
        'Compilation',
        'Cougar',
        'Couple',
        'Creampie',
        'Cum Swallow',
        'Cum Swap',
        'Cumshot',
        'Dancing',
        'Deep Throat',
        'Dildo',
        'Doctor',
        'Doggystyle',
        'Double Penetration',
        'Drunk',
        'Ebony',
        'Emo',
        'Ethnic',
        'European',
        'Face Sitting',
        'Facial',
        'Fantasy',
        'Fat',
        'Feet',
        'Fetish',
        'Fisting',
        'Flexible',
        'Food',
        'Footjob',
        'Foreplay',
        'Foursome',
        'Funny',
        'Gagging',
        'Gang Bang',
        'Girlfriend',
        'Glasses',
        'Glory Hole',
        'Gonzo',
        'Group',
        'Hairy',
        'Handjob',
        'Hardcore',
        'High Heels',
        'Homemade',
        'House Wife',
        'Indian',
        'Interracial',
        'Japanese',
        'Jeans',
        'Kissing',
        'Latina',
        'Legs',
        'Lesbian',
        'Lingerie',
        'Machine',
        'Massage',
        'Masturbation',
        'Mature',
        'Midget',
        'MILF',
        'Nipples',
        'Nurse',
        'Office',
        'Oral',
        'Orgasm',
        'Orgy',
        'Outdoor',
        'Panties',
        'Pantyhose',
        'Party',
        'Petite',
        'POV',
        'Pregnant',
        'Public',
        'Pussy',
        'Reality',
        'Redhead',
        'Rimjob',
        'Rough Sex',
        'Russian',
        'Schoolgirl',
        'Shaving',
        'Shoes',
        'Sleeping',
        'Small Tits',
        'Smoking',
        'Softcore',
        'Solo',
        'Spanking',
        'Sports',
        'Squirting',
        'Stockings',
        'Strapon',
        'Striptease',
        'Swinger',
        'Tattoo',
        'Teacher',
        'Teen',
        'Threesome',
        'Tit Fuck',
        'Toy',
        'Tranny',
        'Uniform',
        'Upskirt',
        'Voyeur',
        'Weird',
        'Wrestling'
    );

    static function customPagination($totalResults, $page = 1, $limit = 25)
    {
        /* pagination */
        $pagination = array();
        $pagination['totalResults'] = $totalResults;
        $pagination['searchLimit'] = $limit;
        $pagination['searchPage'] = $page;
        $pagination['searchPagesLimit'] = 10;
        $pagination['searchPagesActual'] = (int)floor($pagination['totalResults'] / $limit);
        $pagination['searchPages'] = $pagination['searchPagesActual'];
        if ($pagination['searchPagesActual'] > $pagination['searchPagesLimit']) {
            $pagination['searchPages'] = $pagination['searchPagesLimit'];
        }

        return $pagination;
    }

    static function getRealIp()
    {
        // GET REAL VISITOR IP
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    static function slugify($text,$location = 'n-a')
    {
        // replace .' by blank
        $text = str_replace(".", '', $text);
        $text = str_replace("'", '', $text);
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        if($text == ''){
            $text = $location;
        }

        return $text;
    }
    static function slugToString($slug)
    {
        return str_replace("-"," ",$slug);
    }


    static function curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $return = curl_exec($ch);
        curl_close($ch);

        return $return;
    }

    static function curlByProxy($url,$proxy){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, '199.227.40.31:80');
        //curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $proxyRequest = curl_exec($ch);
        curl_close($ch);
        return $proxyRequest;
    }


}
