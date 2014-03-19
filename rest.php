<?php
require_once("PHPRest.class.php");

$PHPRest =& new PHPRest('phprest.ini');
$PHPRest->exec();

/*
echo '<pre>';
var_dump($PHPRestSQL->output);
//*/

?>