<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 09.06.16
 * Time: 4:59
 */

require __DIR__ .'/../vendor/autoload.php';

$parser = new \jakulov\HyperParser\Parser();

$url = 'https://www.avito.ru/kazan/avtomobili';

$pattern = [
    'cars' => [
        'selector' => '.item_table',
        'fields' => [
            'url' => '.item-description-title-link|href',
            'photo' => '.photo-count-show|src',
            'title' => '.item-description-title-link',
            'price' => '.about',
            'date' => '.date',
        ],
    ],
    'current' => '.pagination-page_current'
];

$found = true;

$cars = [];
$parseUrl = $url;
$pageLimit = 5;
$currentPage = 0;

$timeStart = microtime(true);

while($found) {
    try {
        $data = $parser->parseUrl($parseUrl, $pattern);
        $bulkUrls = [];
        if ($data && $data['cars']) {
            foreach ($data['cars'] as $car) {
                $price = '';
                if (preg_match('/(.*)руб/', $car['price'][0], $m)) {
                    $price = str_replace(' ', '', isset($m[1]) ? $m[1] : $price);
                }

                $carUrl = 'https://www.avito.ru' . $car['url'][0];
                $cars[$carUrl] = [
                    'url' => $carUrl,
                    'photo' => $car['photo'][0],
                    'title' => $car['title'][0],
                    'price' => $price,
                    'date' => date('Y-m-d H:i:s', strtotime(dateRusToEn($car['date'][0]))),
                ];
                $bulkUrls[] = $carUrl;
            }

            $bulkData = $parser->bulkParse($bulkUrls, ['description' => '#desc_text']);
            foreach ($bulkData as $carUrl => $carDescription) {
                $cars[$carUrl]['description'] = $carDescription['description'][0];
            }

        } else {
            $found = false;
        }

        $currentPage = (int)$data['current'][0];

        echo 'Parsed page '. $currentPage .'...' . PHP_EOL;
        if($currentPage == $pageLimit) {
            break;
        }
        if($found && $currentPage) {
            $parseUrl = $url . '?p=' . ($currentPage + 1);
        }
        else {
            $found = false;
        }
    }
    catch(\Exception $e) {
        $found = false;
        var_dump($e->getMessage());
    }
}

var_dump($cars);

$timeDone = round(microtime(true) - $timeStart, 2);

echo 'Found '. count($cars) .' by ' . $timeDone .' seconds' . PHP_EOL;

/**
 * @param string $date
 * @return string
 */
function dateRusToEn($date)
{
    return str_ireplace(
        ['Вчера', 'Сегодня', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
        ['Yesterday', 'Today', 'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'], $date);
}