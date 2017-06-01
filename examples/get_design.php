<?php

require_once( __DIR__ . '/../vendor/autoload.php');

use Affinity\DesignsClient;

/*
 * Get a single design as specified by ID.
 *
 * Instead of polling this endpoint to check whether or
 * not a design has been reviewed, consider setting up a webhook
 * to notify your application when a review event has occurred.
 */
$apiKey = 'enter-your-api-key-here';
$designId = 44502;

$designs = new DesignsClient($apiKey);

$design = $designs->getDesign($designId);

echo $design['data']['title'];
