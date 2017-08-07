<?php
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
$search_text = $_POST['search'];
if (mb_strlen($search_text) > 0){
/*
$sql = "SELECT * FROM ul_sm WHERE (LOWER(replace(naim_ul, '\"', ' ')) LIKE LOWER('%$search_text%'))
                              OR (LOWER(regexp_replace(address, ',|\"|(|)|,', ' ')) LIKE LOWER('%$search_text%'))
                              OR (ogrn LIKE '%$search_text%')
                              OR (inn LIKE '%$search_text%')
                              OR (LOWER(rukovoditel_fio) LIKE LOWER('%$search_text%'))
                                  LIMIT 10";
*/

    $sql = "SELECT * FROM ul_sm WHERE (LOWER(replace(naim_ul, '\"', ' ')) LIKE LOWER('%$search_text%'))
                              OR (lower(regexp_replace(address::text, ',|\"|(|)|.'::text, ''::text, 'g'::text)) LIKE LOWER('%$search_text%'))
                              OR (ogrn LIKE '%$search_text%')
                              OR (inn LIKE '%$search_text%')
                              OR (LOWER(rukovoditel_fio) LIKE LOWER('%$search_text%'))
                                  LIMIT 10";

    //$sql = "SELECT * FROM ul_sm WHERE (LOWER(replace(column_to_search, '\"', '')) LIKE LOWER('%$search_text%')) LIMIT 100";
$sth = $dbh->prepare($sql);
$sth->execute();
?>
<div class="card">
    <ul class="mdl-list">
            <?php
            echo $sql;
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                // do something awesome with row
                $data_prekr = $row['data_prekr'];
                if (is_null($row['data_prekr'])){
                    $prekr_class = '';
                }
                else {
                    $prekr_class = ' strike';
                }
                ?>
                <li class="mdl-list__item mdl-list__item--two-line">
    <span class="mdl-list__item-primary-content">
      <h5 class="h5-responsive<?= $prekr_class?>"><a
                  href="/company/<?= $row['ogrn'] ?>/"><?= $row['naim_ul'] ?></a></h5>
      <span class="mdl-list__item-sub-title"><?= $row['address'] ?></span>
    </span>
                    <span class="mdl-list__item-secondary-content hidden-sm-down">
      <span class="mdl-list__item-secondary-info">ОГРН <?= $row['ogrn'] ?></span>
        <span class="mdl-list__item-secondary-info">ИНН <?= $row['inn'] ?></span>
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