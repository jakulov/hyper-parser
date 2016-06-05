<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 05.06.16
 * Time: 15:30
 */

require __DIR__ .'/../vendor/autoload.php';

$parser = new \jakulov\HyperParser\Parser();

$url1 = 'http://lenta.ru';

$pattern1 = [
    'links' => '.b-yellow-box .item a|href',
];

$data1 = $parser->parseUrl($url1, $pattern1);

$urls = [];
foreach($data1['links'] as $link) {
    if($link) {
        $urls[] = $url1 . $link;
    }
}

//var_dump($urls);

$pattern2 = [
    'title' => 'title',
    'img' => '.b-topic__title-image img|src',
    'text' => '.b-text|innertext',
];

$data2 = $parser->bulkParse($urls, $pattern2, false);

foreach($data2 as $url => $news) {
    if(is_array($news)) {
        echo PHP_EOL . '==============' . PHP_EOL;
        echo $url . PHP_EOL;
        echo 'Title: ' . $news['title'][0] . PHP_EOL;
        echo 'IMG: ' . $news['img'][0] . PHP_EOL . PHP_EOL;
        echo 'Text: ' . $news['text'][0] . PHP_EOL . PHP_EOL;
    }
    else {
        echo 'ERROR: '. $news . PHP_EOL . PHP_EOL;
    }
}

