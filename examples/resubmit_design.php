<?php

require_once( __DIR__ . '/../vendor/autoload.php');

use Affinity\DesignsClient;

/*
 * Submit a new version of an existing a product design.
 *
 * The design's image is uploaded inline as a
 * base64-encoded string ("image"), and a
 * filename attribute ("image_filename").
 */
$apiKey = 'enter-your-api-key-here';

$designs = new DesignsClient($apiKey);

$image = file_get_contents(__DIR__ . '/example_image.png');
$designData = [
    'title'               => 'My Excellent Shirt',
    'internal_code'       => 'mydesign-01',
    'product_category_id' => 156,
    'primary_client_id'   => 1007,
    'description'         => 'This is my favorite shirt ever. Please enjoy',
    'image'               => base64_encode($image),
    'image_filename'      => 'excellent_shirt.png',
];

$response = $designs->uploadDesign($designData);

$id = $response['id'];

$newImage = file_get_contents(__DIR__ . '/example_image2.png');

$resubmissionData = [
    'image' => base64_encode($newImage),
    'image_filename' => 'example_image2.png',
];
$resubmittedDesign = $designs->resubmitDesign($id, $resubmissionData);

var_dump($resubmittedDesign);

