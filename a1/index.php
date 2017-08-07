<?php
$start_time = microtime();
$start_array = explode(" ",$start_time);
$start_time = $start_array[1] + $start_array[0];
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
include('/var/www/www-root/data/php/classes/HeaderDisplay.php');
include "function.php";
$obj = New HeaderDisplay ('Администрирование сайта', '', '');
$obj->f_display();
//$nRows_ul = $dbh->query('select count(*) from ul')->fetchColumn();
$nRows_ul = 0;

$nRows_files = $dbh->query('select count(*) from files')->fetchColumn();
$nRows_files_not_count_org = $dbh->query('select count(*) from files where count_org is null')->fetchColumn();
$nRows_files_count_org = $dbh->query('select sum(count_org) from files')->fetchColumn();

$sql_Tables_size = 'select table_name, pg_relation_size(table_name) as table_size
from information_schema.tables
where table_schema = \'public\'
order by 2';

$sth_tables = $dbh->prepare($sql_Tables_size);
$sth_tables->execute();


$function = '';
if (isset($_GET['function']))
{
    $function = $_GET['function'];
}
if ($function=='add_files')
{
    add_files();
}
elseif ($function=='parse_ul')
{
    parse();
}
elseif ($function=='trunc_files') {
    $stmt = $dbh->prepare('TRUNCATE TABLE files');
    $stmt->execute();
}
elseif ($function=='trunc_ul') {
    $stmt = $dbh->prepare('TRUNCATE TABLE ul;TRUNCATE TABLE dolgn_fl;TRUNCATE TABLE dolgn_fl_diskv;TRUNCATE TABLE dolgn_fl_ned_dan;TRUNCATE TABLE license;
    TRUNCATE TABLE license_mesto_deistv;TRUNCATE TABLE license_vid_deyat;TRUNCATE TABLE ned_adres_ul;TRUNCATE TABLE status;TRUNCATE TABLE uchr_fl;
    TRUNCATE TABLE uchr_fl_ned_dan;TRUNCATE TABLE uchr_fl_obrem;TRUNCATE TABLE upr_org;TRUNCATE TABLE upr_org_ned_dan;
    TRUNCATE TABLE zap_egrul;TRUNCATE TABLE zap_egrul_pred_doc;TRUNCATE TABLE zap_egrul_status_zap;TRUNCATE TABLE zap_egrul_svid;');
    $stmt->execute();
}
elseif ($function=='count_org')
{
    count_org();
}
?>

<body class="fixed-sn white-skin">
<?php
include('/var/www/www-root/data/php/classes/admin/MenuDisplay.php');
$obj = New MenuDisplay ('Администрирование сайта');
$obj->f_display();
?>

<!-- Start your project here-->
<!--Main layout-->
<main class="">
    <div class="container-fluid">
            <!--First row-->
            <div class="row">
                <!--First column-->
                <div class="col-md-3">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-block">
                            <!--Title-->
                            <h4 class="card-title">files: <?= $nRows_files ?></h4>
                            <hr>

                            <a href="?function=add_files" class="btn btn-primary">Заполнить</a>
                            <a href="?function=trunc_files" class="btn btn-danger waves-effect waves-light">Очистить</a>
                            <a href="?function=count_org" class="btn btn-info">Количество орг</a><br>
                            Кол орг в файлах: <?= number_format($nRows_files_count_org,0,'.',' ')?>
                        </div>
                        <!--/.Card content-->

                        <!-- Card footer -->
                        <div class="card-data">
                            <ul>
                                <li>Не обработано: <?= $nRows_files_not_count_org ?></li>
                            </ul>
                        </div>
                        <!-- Card footer -->

                    </div>
                    <!--/.Card-->
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-block">
                            <!--Title-->
                            <h4 class="card-title">Размеры таблиц</h4>
                            <hr>
                            <table>
                            <?php
                            $total_size = 0;
                                while ($row = $sth_tables->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                    <tr><td><?= $row['table_name'] ?></td><td><?= number_format($row['table_size'],0,'.',' ') ?></td></tr>
                            <?php
                                    $total_size = $total_size + $row['table_size'];
                                }
                            ?>
                                <tr><td>Всего</td><td><?= number_format($total_size,0,'.',' ')?></td></tr>
                            </table>
                        </div>
                    </div>
                </div>

            </div>




    </div>

</main>
<!--/Main layout-->
<?php

include('/var/www/www-root/data/php/classes/FooterDisplay.php');
$obj = New FooterDisplay ();
$obj->f_display();
include('/var/www/www-root/data/php/inc/scripts.php');
$end_time = microtime();
$end_array = explode(" ",$end_time);
$end_time = $end_array[1] + $end_array[0];
$time = round ($end_time - $start_time,5);
?>
<p class="text-right"><?= $time ?> сек.</p>
</body>

</html>