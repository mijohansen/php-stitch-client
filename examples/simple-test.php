<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 19/01/2018
 * Time: 00:42
 */

use Stitch\StitchClient;

include_once '../vendor/autoload.php';


$config = [
    "client_id" => "1234",
    "token" => "xxx"
];
$client = new StitchClient($config);
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

