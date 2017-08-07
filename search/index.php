<?php
$start_time = microtime();
$start_array = explode(" ",$start_time);
$start_time = $start_array[1] + $start_array[0];
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
include('/var/www/www-root/data/php/classes/HeaderDisplay.php');
$obj = New HeaderDisplay ('Поиск', '', '');
$obj->f_display();
?>
<body class="fixed-sn white-skin">
<?php
include('/var/www/www-root/data/php/classes/MenuDisplay.php');
$obj = New MenuDisplay ('Поиск');
$obj->f_display();
?>
<main class="">
    <div class="container-fluid">
        <div class="row mb-r">
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
                <div id="container-search">
                </div>
            </div>
            <div class="col-lg-3 col-md-12 hidden-sm-down">

            </div>
        </div>
    </div>
</main>
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
<script type="text/javascript">
    function update(){
        var searchText = document.getElementById('search').value;
        $.ajax({
            type: "POST",
            cache: false,
            url: "/search/ajax/company.php",
            data: "search="+searchText,
            success: function(html){
                $("#container-search").html(html);
            }
        });
    }
</script>
<p class="text-right"><?= $time ?> сек.</p>
</body>
</html>