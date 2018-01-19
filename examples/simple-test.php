<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 19/01/2018
 * Time: 00:42
 */

use Stitch\StitchClient;

include_once '../vendor/autoload.php';

$client_id = xxx;
$token = "5f1bdb0869048982820fdbcc62c012a9363ac05ae083b85c1b261fbafbaef753";

$client = new StitchClient($client_id, $token);
$faker = Faker\Factory::create();

for ($i = 1; $i <= 10; $i++) {
    $message = [
        'action' => 'upsert',
        'table_name' => 'test_table',
        'key_names' => ['id'],
        'sequence' => $i,
        'data' => [
            'id' => $i,
            'value' => $faker->name
        ]
    ];
    $client->push($message);
}
try {
    $client->flush();
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

