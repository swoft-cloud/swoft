<?php
$beans = [
    'application' => \DI\object(\swoft\web\Application::class)
                    ->property('id', \DI\get('id'))
                    ->property('name', \DI\get('name'))
                    ->property('params', \DI\get('params'))
                    ->property('basePath', \DI\get('basePath'))
                    ->property('runtimePath', \DI\get('runtimePath'))
                    ->property('settingPath', \DI\get('settingPath'))
];
return $beans;