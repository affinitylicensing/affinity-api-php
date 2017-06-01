<?php

require_once( __DIR__ . '/../vendor/autoload.php');

use Affinity\DesignsClient;

/*
 * Delete a design specified by ID.
 *
 * This example uploads a design, then deletes it.
 *
 * Designs may only be deleted if they are still awaiting review.
 */
$apiKey = 'enter-your-api-key-here';

$designs = new DesignsClient($apiKey);

// Upload a design to delete
$image = file_get_contents(__DIR__ . '/example_image.png');
$designData = [
    'title'               => 'Deletable Design',
    'internal_code'       => 'delete-me01',
    'product_category_id' => 156,
    'primary_client_id'   => 1007,
    'description'         => 'Uploading this was a mistake',
    'image'               => base64_encode($image),
    'image_filename'      => 'delete_me.png',
];
$response = $designs->uploadDesign($designData);

$designId = $response['id'];

// Delete the design we just uploaded
$response = $designs->deleteDesign($designId);
var_dump($response);

