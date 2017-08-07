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


$nRows_ul_sm = $dbh->query('select count(*) from ul_sm')->fetchColumn();
$nRows_ul_sm_not_index = $dbh->query('select count(*) from files where indexed_sm is null')->fetchColumn();

$function = '';
if (isset($_GET['function']))
{
    $function = $_GET['function'];
}
if ($function=='parse_sm')
{
    parse_sm();
}
elseif ($function=='trunc_sm') {
    $stmt = $dbh->prepare('TRUNCATE TABLE ul_sm');
    $stmt->execute();
    $stmt_1 = $dbh->prepare('UPDATE files SET indexed_sm = NULL');
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
                        <h4 class="card-title">ul_sm: <?= number_format($nRows_ul_sm,0,'.',' ') ?></h4>
                        <hr>

                        <a href="?function=parse_sm" class="btn btn-primary">Заполнить</a>
                        <a href="?function=trunc_sm" class="btn btn-danger waves-effect waves-light">Очистить</a>
                    </div>
                    <!--/.Card content-->

                    <!-- Card footer -->
                    <div class="card-data">
                        <ul>
                            <li>Не обработано: <?= number_format($nRows_ul_sm_not_index,0,'.',' ') ?></li>
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