<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 05.06.16
 * Time: 13:41
 */

require __DIR__ .'/../vendor/autoload.php';

$parser = new \jakulov\HyperParser\Parser();

$url = 'http://www.florist.ru';

$pattern = [
    'items' => [
        'selector' => '.bouquets-page .article',
        'fields' => [
            'title' => '.title a',
            'img' => '.bouquet-main-image|src',
            'price' => '.price',
            'url' => '.title a|href',
        ],
    ]
];

$data = $parser->parseUrl($url, $pattern);

foreach($data['items'] as $item) {
    echo PHP_EOL . '================' . PHP_EOL;
    echo 'Item: '. $item['title'][0] . PHP_EOL;
    echo 'URL: '. $item['url'][0] . PHP_EOL;
    echo 'IMG: '. $item['img'][0] . PHP_EOL;
    echo 'price: '. $item['price'][0] . PHP_EOL;
}