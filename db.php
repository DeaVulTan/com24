<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 05.04.2017
 * Time: 16:03
 */
$configs = include('/var/www/www-root/data/php/config/config.php');
$dbh = new PDO("pgsql:host=$configs[host];dbname=$configs[db]", $configs['username'], $configs['password']);

$ogrn = 1;
$sql = "SELECT * FROM ul_sm WHERE ogrn = ?";
$sth = $dbh->prepare($sql);
$sth->execute(array($ogrn));