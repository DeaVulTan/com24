<?php
$start_time = microtime();
$start_array = explode(" ",$start_time);
$start_time = $start_array[1] + $start_array[0];
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
include('/var/www/www-root/data/php/classes/HeaderDisplay.php');
include "../function.php";
$obj = New HeaderDisplay ('Краткая таблица ul_sm', '', '');
$obj->f_display();


$nRows_files_not_index = $dbh->query('select count(*) from files where indexed_update is null')->fetchColumn();
$nRows_ul_update = $dbh->query('select count(*) from ul_update')->fetchColumn();

$function = '';
if (isset($_GET['function']))
{
    $function = $_GET['function'];
}
if ($function=='parse_update')
{
    parse_update();
}
elseif ($function=='trunc_update') {
    $stmt = $dbh->prepare('TRUNCATE TABLE ul_update');
    $stmt->execute();
    $stmt_1 = $dbh->prepare('UPDATE files SET indexed_update = NULL');
    $stmt_1->execute();
}
?>

<body class="fixed-sn white-skin">
<?php
include('/var/www/www-root/data/php/classes/admin/MenuDisplay.php');
$obj = New MenuDisplay ('Краткая таблица ul_sm');
$obj->f_display();
?>

<!-- Start your project here-->
<!--Main layout-->
<main class="">
    <div class="container-fluid">


        <div class="row">
            <!--Third column-->
            <div class="col-md-3">
                <!--Card-->
                <div class="card">
                    <!--Card content-->
                    <div class="card-block">
                        <!--Title-->
                        <h4 class="card-title">ul_update: <?= number_format($nRows_ul_update,0,'.',' ') ?></h4>
                        <hr>

                        <a href="?function=parse_update" class="btn btn-primary">Заполнить</a>
                        <a href="?function=trunc_update" class="btn btn-danger waves-effect waves-light">Очистить</a>
                    </div>
                    <!--/.Card content-->

                    <!-- Card footer -->
                    <div class="card-data">
                        <ul>
                            <li>Не обработано: <?= $nRows_files_not_index ?></li>
                        </ul>
                    </div>
                    <!-- Card footer -->

                </div>
                <!--/.Card-->
            </div>
            <!--/Third column-->
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