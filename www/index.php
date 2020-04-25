<?php

ini_set( 'session.cookie_httponly', 1 );
session_start();


$uber_root = str_replace('www', '', $_SERVER['DOCUMENT_ROOT']);
require_once $uber_root.'\app\conf\Env.php';
$env = new Sysel\Conf\Env($uber_root);
$webroot = $env->get_param('webroot');


use Sysel\Conf\Router;
use Sysel\conf\Exceptions\Controller_exception;
use Sysel\Pages\Error\Error_controller;
$router = new Router($env);
$control = $router->load($_SERVER['REQUEST_URI']);

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$control->get_page_title()?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?=$webroot?>/img/sysel.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="<?=$webroot?>/css/common.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body>
        <?
            try {
                $control->zobraz();                
            }
             catch (Controller_exception $e) {
                 $error = new Error_controller($env, $env->get_param('root').'/pages/error', array());
                 $error->set_msg($e->getMessage());
                 $error->zobraz();
             }
        ?>
    </body>
</html>

