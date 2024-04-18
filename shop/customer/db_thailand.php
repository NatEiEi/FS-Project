<?ob_start();?>
<?php
$info = array(
    'host' => 'mysqldb',
    'user' => 'admin',
    'password' => 'example',
    'dbname' => 'thailand'
);
$conn = mysqli_connect($info['host'], $info['user'], $info['password'], $info['dbname']) or die('Error connection database!');
mysqli_set_charset($conn, 'utf8');