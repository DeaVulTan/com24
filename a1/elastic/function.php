<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 06.04.2017
 * Time: 14:49
 */
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
require '/var/www/www-root/data/composer/vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();

function index_count(){
    global $client;
    $text_to_search = '*';
    $params = [
        'index' => 'ul_index',
        'type' => 'ul_type',
        'size' => 3000000,
        'body' => [
            'query' => [
                'query_string' => [
                    'query' => $text_to_search,
                    'fields' => ['ogrn','inn','naim_ul','address','rukovoditel_fio']
                ]
            ]
        ]
    ];
    $results = $client->search($params);
    //$count = $client->count($params);
    return count($results['hits']['hits']).' ';;
}

function indexing(){
    global $client, $dbh;
    //$sql_select = "SELECT * FROM ul_sm ORDER BY ogrn LIMIT 500000";
    $sql_select = "SELECT * FROM ul_sm ORDER BY ogrn LIMIT 500000 OFFSET 9500000";
    //$sql_select = "SELECT * FROM ul_sm WHERE indexed_elastic IS NULL";
    $sth_select = $dbh->prepare($sql_select);
    $sth_select->execute();
    $params = ['body' => []];
    $i = 1;
    while ($row = $sth_select->fetch(PDO::FETCH_ASSOC)) {

        $params['body'][] = [
            'index' => [
                '_index' => 'ul_index',
                '_type' => 'ul_type',
                '_id' => $row['ogrn']
            ]
        ];
        $params['body'][] = [
            'ogrn' =>  $row['ogrn'],
            'inn' => $row['inn'],
            'naim_ul' => trim($row['naim_ul']),
            'address' => trim($row['address']),
            'rukovoditel_fio' => trim($row['rukovoditel_fio'])
        ];
        // Every 1000 documents stop and send the bulk request
        if ($i % 10000 == 0) {
            $responses = $client->bulk($params);

            // erase the old bulk request
            $params = ['body' => []];

            // unset the bulk response when you are done to save memory
            unset($responses);
        }
        $i++;
    }

// Send the last batch if it exists
    if (!empty($params['body'])) {
        $responses = $client->bulk($params);
    }
}
function search($text_to_search){
    $text_to_search = '*'.$text_to_search.'*';
    //$text_to_search = '*';
    global $client;
    $params = [
        'index' => 'ul_index',
        'size' => 20,
        'type' => 'ul_type',
        'body' => [
            'query' => [
                'query_string' => [
                    'query' => $text_to_search,
                    'fields' => ['ogrn','inn','naim_ul','address','rukovoditel_fio']
                ]
            ]
        ]
    ];

    $results = $client->search($params);
    print_r($results);
    echo count($results['hits']['hits']).' ';
    //echo count($results['hits']['total']);
}
function search_REZ($text_to_search){
    $text_to_search = '*'.$text_to_search.'*';
    //$text_to_search = '*';
    global $client;
    $params = [
        'index' => 'ul_index',
        'size' => 20,
        'type' => 'ul_type',
        'body' => [
            'query' => [
                'wildcard' => [
                    'naim_ul' => $text_to_search,
                ]
            ]
        ]
    ];

    $results = $client->search($params);
    print_r($results);
    echo count($results['hits']['hits']).' ';
    //echo count($results['hits']['total']);
}
function del_index(){
    global $client;
    $params = ['index' => 'ul_index'];
    $results = $client->indices()->delete($params);
    print_r($results);
}