<?php
require_once __DIR__. '/bootstrap.php';

$loader = new Twig_Loader_Filesystem(__DIR__."/../app/views");
$twig = new Twig_Environment($loader);

echo $twig->render('/main/layout.html', array('name' => 'Fabien'));