<?php

require_once( __DIR__ . '/../vendor/autoload.php');

use Affinity\DesignsClient;

/*
 * Get a list of previously uploaded designs.
 *
 * Results are paginated so it is necessary to make multiple requests
 * iterating through setPage().
 */
$apiKey = 'enter-your-api-key-here';

$designs = new DesignsClient($apiKey);

$i = 1;
while ($designPage = $designs->getList())
{

    foreach($designPage['data']  as $design) {
        echo $design['title'] . "\n";
    }

    if ($designPage['meta']['pagination']['count'] < 1) {
        break;
    }

    $i++;
    $designs->setPage($i);
}
