<?php

ini_set( 'session.cookie_httponly', 1 );
session_start();

$uber_root = str_replace('www', '', $_SERVER['DOCUMENT_ROOT']);
require_once $uber_root.'\app\conf\Env.php';
$env = new Sysel\Conf\Env($uber_root);

use Sysel\Pages\Polozky_ve_sklade\Polozky_ve_sklade_model;
use Sysel\conf\Exceptions\Polozky_ve_sklade_exception;

$model = new Polozky_ve_sklade_model($env);
$w_id = trim($_POST['w_id']);
$it_id = trim($_POST['it_id']);

if (empty($w_id) or empty($it_id)) {
    echo '??';
}
else {
    try {
        echo $model->maximalne_polozek_na_sklad($w_id, $it_id);
    }
    catch (Polozky_ve_sklade_exception $e) {
        echo '??';
    }
}