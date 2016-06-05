<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 05.06.16
 * Time: 12:19
 */

require __DIR__ .'/../vendor/autoload.php';

$parser = new \jakulov\HyperParser\Parser();

$url = 'https://en.wikipedia.org/wiki/Adam_Smith';
$pattern = [
    'name' => '#firstHeading',
    'img' => '.image img|src',
    'bio' => '#mw-content-text p|innertext',
    'tags' => '#mw-normal-catlinks a',
];

$data = $parser->parseUrl($url, $pattern);

echo 'Name: '. ($data['name'][0]) . PHP_EOL;
echo 'Photo: '. ($data['img'][0]) . PHP_EOL . PHP_EOL;
echo 'Bio: '. strip_tags($data['bio'][0]) . PHP_EOL . PHP_EOL;
echo '===================== '. PHP_EOL;
echo 'Tags: '. join(', ', array_slice($data['tags'], 1)) . PHP_EOL . PHP_EOL;
