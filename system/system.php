<?php 

    if (!isset($GLOBALS['config']))
    {
       $config = array();
       include_once './config/config.php';
       $GLOBALS['config'] = $config;
    }  

?>