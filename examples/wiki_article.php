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

echo 'Name: '. array_shift($data['name']) . PHP_EOL;
echo 'Photo: '. array_shift($data['img']) . PHP_EOL . PHP_EOL;
echo 'Bio: '. strip_tags(array_shift($data['bio'])) . PHP_EOL . PHP_EOL;
echo '===================== '. PHP_EOL;
echo 'Tags: '. join(', ', array_slice($data['tags'], 1)) . PHP_EOL . PHP_EOL;
