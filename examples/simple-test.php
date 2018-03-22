<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 19/01/2018
 * Time: 00:42
 */

include_once __DIR__.'/../vendor/autoload.php';

use Stitch\StitchClient;

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."config.json"),JSON_OBJECT_AS_ARRAY);
$client = new StitchClient($config);
$faker = Faker\Factory::create();

for ($i = 1; $i <= 10; $i++) {
    $message = [
        'action' => 'upsert',
        'table_name' => 'float_test_table',
        'key_names' => ['id'],
        'sequence' => $i,
        'data' => [
            'id' => $i + 10,
            'value' => $faker->name,
            'this_should_be_a_float' => floatval("1.5")
        ]
    ];
    $client->push($message);
}
try {
    $result = $client->flush();
    print_r($result);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

