<?php
$start_time = microtime();
$start_array = explode(" ",$start_time);
$start_time = $start_array[1] + $start_array[0];
$configs = include('/var/www/www-root/data/php/config/config.php');

$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
include('/var/www/www-root/data/php/classes/HeaderDisplay.php');


    $obj = New HeaderDisplay ('Информация о юридических лицах. Реестр ЕГРЮЛ',
        'Com24.su - Актуальная информация о юридических лицах, учредителях, руководстве с возможностью поиска',
        'информация о организациях юридических лицах реестр егрюл', '');
    $obj->f_display();
?>

<body class="fixed-sn white-skin">
<?php
include('/var/www/www-root/data/php/classes/MenuDisplay.php');
$obj = New MenuDisplay ('Справочник предприятий и организаций');
$obj->f_display();

?>

<!-- Start your project here-->
<!--Main layout-->
<main class="">

    <div class="container-fluid">


        <!-- Second row -->

        <div class="row mb-r">

            <!-- First column -->

            <div class="col-lg-9 col-md-12">
                <!--Card-->
                <div class="card wow fadeIn" data-wow-delay="0.3s">

                    <!--Card content-->
                    <div class="card-block">
                        <div class="md-form">
                            <input id="input_text" type="text" length="10">
                            <label for="input_text">ИНН, ОГРН, Название, ФИО</label>
                        </div>
                    </div>
                    <!--/.Card content-->

                </div>
                <!--/.Card-->
                <!--Panel-->
                <div class="card wow fadeIn" data-wow-delay="0.3s">

                    <h1 class="card-title h1-responsive">Список банков с действующей лицензией</h1>
                    <ul class="mdl-list">
                    <?php
                    //$total = $dbh->query('select count(*) from ul_sm')->fetchColumn();
                    $total = 928879;
                    $per_page = 100; // количество записей на страницу
                    $total_pages = ceil($total / $per_page); // всего страниц
                    if (isset($_GET['page'])) {
                        $page = (int)($_GET['page'] - 1);
                        $page_prev = $page;
                        $page_next = $page + 2;
                        if ($page == 0) {
                            $link_prev = '';
                            $link_next = '<link rel="next" href="http://katashi.ru/bank/?page=' . $page_next . '">';
                            $meta = '';
                        } elseif ($page == 1) {
                            $link_prev = '<link rel="prev" href="http://katashi.ru/bank/">';
                            $link_next = '<link rel="next" href="http://katashi.ru/bank/?page=' . $page_next . '">';
                            $meta = '<meta name="robots" content="noindex, follow" />';
                        } elseif ($page == $total_pages - 1) {
                            $link_prev = '<link rel="prev" href="http://katashi.ru/bank/?page=' . $page_prev . '">';
                            $link_next = '';
                            $meta = '<meta name="robots" content="noindex, follow" />';
                        } else {
                            $link_prev = '<link rel="prev" href="http://katashi.ru/bank/?page=' . $page_prev . '">';
                            $link_next = '<link rel="next" href="http://katashi.ru/bank/?page=' . $page_next . '">';
                            $meta = '<meta name="robots" content="noindex, follow" />';
                        }

                    } else {
                        $page = 0;
                        $link_prev = '';
                        $link_next = '<link rel="next" href="http://katashi.ru/bank/?page=2">';
                    }
                    $start = abs($page * $per_page);
                    $sql = "SELECT * FROM ul_sm ORDER BY naim_ul LIMIT $per_page OFFSET $start";
                    $sth = $dbh->prepare($sql);
                    $sth->execute();
                    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                        // do something awesome with row
                        ?>
                        <li class="mdl-list__item mdl-list__item--two-line">
    <span class="mdl-list__item-primary-content">
      <h5 class="h5-responsive"><a
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
                <!--/.Panel-->
                <?php
                include('/var/www/www-root/data/php/inc/pagination.php');
                ?>
            </div>
            <div class="col-lg-3 col-md-12 hidden-sm-down">

            </div>


        </div>

        <!-- /.Second row -->

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