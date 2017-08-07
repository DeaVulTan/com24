<?php
$start_time = microtime();
$start_array = explode(" ",$start_time);
$start_time = $start_array[1] + $start_array[0];
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
include('/var/www/www-root/data/php/func/main.php');
include('/var/www/www-root/data/php/classes/HeaderDisplay.php');
/*
 * Вывод компании
 */
if (isset ($_GET['id']))
{
    $ogrn = (int)$_GET['id'];
    $sql = "SELECT * FROM ul_sm WHERE ogrn = ?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($ogrn));
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    $naim_ul_poln = $row['naim_ul'];
    $address = $row['address'];
    $inn = $row['inn'];
    $rukovoditel_fio = $row['rukovoditel_fio'];
    $rukovoditel_dolgn = $row['rukovoditel_dolgn'];
    $data_prekr = $row['data_prekr'];

    $obj = New HeaderDisplay (htmlspecialchars($naim_ul_poln).' ИНН '.$inn.', ОГРН '.$ogrn,
        htmlspecialchars($naim_ul_poln).' ИНН '.$inn.', ОГРН '.$ogrn.'. Сведения о руководителе, юридическом адресе, учредителях организации',
        htmlspecialchars($naim_ul_poln).' инн '.$inn.', огрн '.$ogrn, '');
    $obj->f_display();

?>
<body class="fixed-sn white-skin">
<?php
include('/var/www/www-root/data/php/classes/MenuDisplay.php');
$obj = New MenuDisplay ($naim_ul_poln);
$obj->f_display();
?>
<main class="">
<div class="row">
    <div class="col-md-8">
        <!--Card-->
        <div class="card wow fadeIn" data-wow-delay="0.3s">

            <!--Card content-->
            <div class="card-block">
                <!--Title-->
                <h4 class="h4-responsive text-xs-center mb-1"><?= $naim_ul_poln ?></h4>
                <hr>
                <!--Text-->
                <dl>
                    <dt>Полное наименование</dt>
                    <dd><?= $naim_ul_poln ?></dd>
                    <dt>Юридический адрес</dt>
                    <dd><?= $address ?></dd>
                    <dt>ОГРН</dt>
                    <dd><?= $ogrn ?></dd>
                    <dt>ИНН</dt>
                    <dd><?= $inn ?></dd>
                    <dt><?= $rukovoditel_dolgn ?></dt>
                    <dd><?= $rukovoditel_fio ?></dd>
                </dl>
                <?php
                if (is_null($row['data_prekr']) !== true){


                ?>
                <!--Card Danger-->
                <div class="card card-danger">
                    <div class="card-block">
                        <p class="white-text">Организация ликвидирована <?= mysql_date($row['data_prekr']) ?></p>
                    </div>
                </div>
                <!--/.Card Danger-->
                <?php } ?>
            </div>
            <!--/.Card content-->

            <!-- Card footer -->
            <div class="card-data">
                <ul>
                    <li><i class="fa fa-clock-o"></i> 05/10/2015</li>
                    <li><a href="#"><i class="fa fa-comments-o"></i>12</a></li>
                    <li><a href="#"><i class="fa fa-facebook"> </i>21</a></li>
                    <li><a href="#"><i class="fa fa-twitter"> </i>5</a></li>
                </ul>
            </div>
            <!-- Card footer -->

        </div>
        <!--/.Card-->

    </div>

</div>



</main>
<?php
}
/*
 * Список компаний
 */
else {
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
                            <div class="md-form input-group">
                                <input id="search" type="text" class="form-control" onkeyup="update();">
                                <span class="input-group-btn">
        <button class="btn btn-primary btn-lg" type="button" onclick="document.getElementById('search').value='';update();">X</button>
    </span>
                                <label for="search">ИНН, ОГРН, Название, ФИО, Адрес</label>
                            </div>
                        </div>
                        <!--/.Card content-->
                    </div>
                    <!--/.Card-->

                <!--Panel-->
                <div id="container-search">
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
                </div>
                <!--/.Panel-->
                <?php
                include('/var/www/www-root/data/php/inc/pagination.php');
                ?>
            </div>
            <div class="col-lg-3 col-md-12 hidden-sm-down">
                <div class="list-group wow fadeIn" data-wow-delay="0.7s">
                    <a href="#" class="list-group-item justify-content-between active">Действующие банки
                        <span class="badge badge-primary badge-pill">639</span>
                    </a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия<span
                                class="badge badge-primary badge-pill">14</span></a>
                    <a href="#" class="list-group-item justify-content-between">Аннулирована лицензия<span
                                class="badge badge-primary badge-pill">4</span></a>
                    <a href="#" class="list-group-item justify-content-between">Ликвидированные банки<span
                                class="badge badge-primary badge-pill">2176</span></a>
                    <a href="#" class="list-group-item justify-content-between">Все банки<span
                                class="badge badge-primary badge-pill">3162</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2017 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2016 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2015 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2014 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2013 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2012 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2011 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2010 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Отозвана лицензия в 2009 г.<span
                                class="badge badge-primary badge-pill">90</span></a>
                    <a href="#" class="list-group-item justify-content-between">Статистика отзывов лицензий</a>
                </div>
            </div>


        </div>

        <!-- /.Second row -->

    </div>

</main>
<!--/Main layout-->
<?php
}
include('/var/www/www-root/data/php/classes/FooterDisplay.php');
$obj = New FooterDisplay ();
$obj->f_display();
include('/var/www/www-root/data/php/inc/scripts.php');
$end_time = microtime();
$end_array = explode(" ",$end_time);
$end_time = $end_array[1] + $end_array[0];
$time = round ($end_time - $start_time,5);
?>
<script type="text/javascript">
    window.update_process = false;
    window.search_text = "";
    function update(){
        var searchText = document.getElementById('search').value;
        if (searchText.length > 2 && !window.update_process) {
            clearTimeout(window.search_timeout);
            window.search_timeout = setTimeout(function(){
                window.update_process = true;
                window.search_text = searchText;
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "/search/ajax/company_el.php",
                    data: "search=" + searchText,
                    success: function (html) {
                        $("#container-search").html(html);
                        window.update_process = false;
                        if (document.getElementById('search').value != window.search_text) {
                            update();
                        }
                    },
                    error: function() {
                        window.update_process = false;
                        if (document.getElementById('search').value != window.search_text) {
                            update();
                        }
                    }
                });
            }, 500);
        }
    }
</script>
<p class="text-right"><?= $time ?> сек.</p>
</body>

</html>