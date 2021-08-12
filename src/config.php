<?php

// Check session status and start session
if(empty(session_id())) session_start();

# Includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

# Imports the Google Cloud client libraries
use Google\Cloud\BigQuery\BigQueryClient;

# My Google Cloud Platform project ID
$projectId = 'asm1cc21bp2';

# Instantiates a client
$bigQuery = new BigQueryClient([
    'projectId' => $projectId
]);
?>