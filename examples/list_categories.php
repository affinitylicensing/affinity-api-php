<?php

require_once( __DIR__ . '/../vendor/autoload.php');

use Affinity\DesignsClient;

/*
 * Get a list of product categories.
 *
 * This can be used to populate a drop-down/etc. for selecting the
 * product category ID passed in the product_category_id field of a
 * design upload request.
 *
 * The values returned here can be cached aggressively by your application -
 * the categories returned will not change unless the categories associated
 * with your license agreements change.
 */

$apiKey = 'enter-your-api-key-here';

$designs = new DesignsClient($apiKey);


$categories = $designs->getCategories();

foreach ($categories['data'] as $category) {
    echo $category['id'] . '-' . $category['name'] . "\n";
}
