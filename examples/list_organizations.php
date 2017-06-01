<?php

require_once( __DIR__ . '/../vendor/autoload.php');

use Affinity\DesignsClient;

/*
 * Get a list of licensed organizations.
 *
 * This can be used to populate a drop-down/etc. for selecting the
 * organization ID passed in the primary_client_id field of a
 * design upload request.
 *
 * The values returned here can be cached by your application, as this
 * data does not change frequently.
 */
$apiKey = 'enter-your-api-key-here';

$designs = new DesignsClient($apiKey);


$orgs = $designs->getOrganizations();

foreach ($orgs['data'] as $org) {
    echo $org['id'] . ' - ' . $org['name'] . "\n";
}
