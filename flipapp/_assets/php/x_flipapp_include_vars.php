<?php 





$ROOT = '/';
$DADFLIP_APPS = 'http://localhost/dadflip/';

$FLIP_APP = $ROOT . 'flipapp';
//--------------------------------

$ASSETS = $FLIP_APP . '/_assets';
$css_dir = $ASSETS . '/css';
$img_dir = $ASSETS . '/img';
$js_dir = $ASSETS . '/js';
$php_dir = $ASSETS . '/php';
//--------------------------------

$O_AUTH = $FLIP_APP . '/_oauth';


$USR = $FLIP_APP . '/_usr';
$usr_php_dir = $FLIP_APP . '/.php';
$usr_media = $FLIP_APP . '/media';



global $userId;
global $userEmail;
global $DADFLIP_APPS, $ROOT, $FLIP_APP, $ASSETS, $O_AUTH, $USR, $css_dir, $img_dir, $jd_dir, $php_dir, $usr_php_dir, $usr_media;
?>