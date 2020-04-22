<?php

ini_set( 'session.cookie_httponly', 1 );
session_start();


$uber_root = str_replace('www', '', $_SERVER['DOCUMENT_ROOT']);
require_once $uber_root.'\app\conf\Env.php';
$env = new Sysel\Conf\Env($uber_root);


use Sysel\Conf\Router;
$router = new Router($env);
$control = $router->load($_SERVER['REQUEST_URI']);

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$control->get_page_title()?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?=$env->get_param('webroot')?>/img/sysel.ico" type="image/x-icon">
    </head>
    <body>
        <?$control->zobraz()?>
    </body>
</html>

