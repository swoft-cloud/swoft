<?php
use Swoft\Web\Request;
use Swoft\Web\Response;
use App\Controllers\IndexController;

/* @var \Swoft\Router\Http\HandlerMapping $router */
$router = \Swoft\App::getBean('httpRouter');

$router->get('/', IndexController::class);
$router->get('/user/{uid}/book/{bid}/{bool}/{name}', function (bool $bool, Request $request,  int $bid, string $name, int $uid, Response $response){
    return ['clouse', $bid, $uid, $bool, $name, get_class($request), get_class($response)];
});
