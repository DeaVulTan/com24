<?php
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);
include "function.php";
include('/var/www/www-root/data/php/classes/HeaderDisplay.php');
$obj = New HeaderDisplay ('ElasticSearch', '', '');
$obj->f_display();
$function = '';
if (isset($_GET['function']))
{
    $function = $_GET['function'];
}
if ($function=='indexing')
{
    indexing();
}
elseif ($function=='search')
{
    search('671050');
}
elseif ($function=='del_index')
{
    del_index();
}
?>

<body class="fixed-sn white-skin">
<?php
include('/var/www/www-root/data/php/classes/admin/MenuDisplay.php');
$obj = New MenuDisplay ('ElasticSearch');
$obj->f_display();
?>

<!-- Start your project here-->
<!--Main layout-->
<main class="">
    <div class="container-fluid">

            <div class="row">
                <div class="col-md-6">

                    <!--Panel-->
                    <div class="card mb-r">
                        <div class="card-header white-text">
                            Количество 
                        </div>
                        <div class="card-block">


                            <a href="?function=indexing" class="btn btn-primary">Индексация</a>
                            <a href="?function=search" class="btn btn-primary">Поиск</a>
                            <a href="?function=del_index" class="btn btn-danger">Очистить индекс</a>
                        </div>
                    </div>
                    <!--/.Panel-->
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
?>
</body>
</html>