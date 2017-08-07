<?php
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
require '/var/www/www-root/data/composer/vendor/autoload.php';

use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();

$search_text = $_POST['search'];
$search_text = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', ' ',$search_text );
if (mb_strlen($search_text) > 2) {

    $params = [
        'index' => 'ul_index',
        'size' => 10,
        'type' => 'ul_type',
        'body' => [
            'query' => [
                'multi_match' => [
                    'query' => '*'.$search_text.'*',
                    'fields' => ['naim_ul'],
                    'type' => 'phrase',
                ]
            ]
        ]
    ];

    $params = [
        'index' => 'ul_index',
        'size' => 10,
        'type' => 'ul_type',
        'body' => [
            'query' => [
                'query_string' => [
                    'query' => '*'.$search_text.'*',
                    'fields' => ['ogrn','inn','naim_ul','address','rukovoditel_fio']
                ]
            ]
        ]
    ];

    $results = $client->search($params);
    $hits = $results['hits']['hits'];

?>
<div class="card">
    <?= $search_text; ?>
    <ul class="mdl-list">
            <?php
            foreach ($hits as $value) {
                $ogrn = $value['_source']['ogrn'];
                $inn = $value['_source']['inn'];
                $naim_ul = $value['_source']['naim_ul'];
                $address = $value['_source']['address'];
                ?>
                <li class="mdl-list__item mdl-list__item--two-line">
    <span class="mdl-list__item-primary-content">
      <h5 class="h5-responsive<?= $prekr_class?>"><a
                  href="/company/<?= $ogrn ?>/"><?= $naim_ul ?></a></h5>
      <span class="mdl-list__item-sub-title"><?= $address ?></span>
    </span>
                    <span class="mdl-list__item-secondary-content hidden-sm-down">
      <span class="mdl-list__item-secondary-info">ОГРН <?= $ogrn ?></span>
        <span class="mdl-list__item-secondary-info">ИНН <?= $inn ?></span>
    </span>
                </li>
                <li class="list-group-separator"></li>
                <?php
            }
            ?>
        </ul>
</div>
<?php
}