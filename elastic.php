<?php

$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);

require '/var/www/www-root/data/composer/vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->build();
/*
$params = [
    'index' => 'my_index',
    'type' => 'my_type',
    'id' => 'my_id',
    'body' => [ 'testField' => 'abc']
];

// Document will be indexed to my_index/my_type/my_id
$response = $client->index($params);

*/

/*
 * Поиск
 */
/*
$params = [
    'index' => 'my_index',
    'type' => 'my_type',
    'body' => [
        'query' => [
            'match' => [
                'testField' => 'abc'
            ]
        ]
    ]
];

$results = $client->search($params);
print_r($results);
*/
/*
 * Удаление записи
 */
/*
$params = [
    'index' => 'my_index',
    'type' => 'my_type',
    'id' => 'my_id'
];

// Delete doc at /my_index/my_type/my_id
$response = $client->delete($params);
*/
/*
 * Удаление индекса
 */
$params = ['index' => 'my_index'];
$response = $client->indices()->delete($params);

