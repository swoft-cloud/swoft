<?php
require_once __DIR__. '/bootstrap.php';

$app = new \Swoft\Console\Console();
$app->run();

exit();
//$input = new \Swoft\Console\Input\Input();
//
//$read = $input->read('select a or b', true);
//echo $read;
//exit();
//\Swoft\Console\Style\Style::init();
//$msg = '<error>stelin</error><yellow>yellow</yellow>';
//
//$msg = \Swoft\Console\Style\Style::t($msg);
//
//echo $msg."\n";

//$a = new ReflectionClass(\Swoft\Console\Command\ServerController::class);
//$m = $a->getMethod('actionStart');
//$b = $m->getDocComment();
//$c = \Swoft\Console\AnnotationParser::tagList($b);
//foreach ($c as $tag => $item) {
//    $msg = trim($item);
//        var_dump(explode("\n", $item));
//    $tag = ucfirst($tag);
////    echo "$tag:\n $item\n";
//}

\Swoft\Console\Style\Style::init();

$list = [
    'Usage:' => [
        'vendor/inhere/console/examples/app [route|command] [arg0 arg1=value1 arg2=value2 ...] [--opt -v -h ...]'
    ],
    'Commands:' => [
        'server' => 'manage http and rpc server',
        'rpc' => 'manage rpc server',
        'db' => 'manage database operations',
    ],
    'Options:' =>[
        '-h,--help' => 'show help information',
        '-v,--version' => 'show version information'
    ]
];

//Description
//  this is a demo independent command. but config use configure(), it like symfony console: argument define by position
//
//Usage
//[-y|--yes] [--opt1 OPT1] [--opt2 [OPT2]] [--] <name> [<sex>] [<age>]
//
//Arguments
//  name  *description for the argument [name], is required
//sex   Description for the argument [sex], is optional
//age   Description for the argument [age], is optional
//
//Options
//-y|--yes   Description for the option [yes], is boolean
//--opt1     *description for the option [opt1], is required
//--opt2     Description for the option [opt2], is optional
//-h|--help  Show help information for the command
//
//    Example
//vendor/inhere/console/examples/app demo john male 43 --opt1 value1
$list = [
    'Description:' => [
        'this is a demo independent command. but config use configure(), it like symfony console: argument define by position'
    ],
    'Usage:' => [
        'home:{command} [arguments] [options]'
    ],
    'Commands:' => [
        'server' => 'manage http and rpc server',
        'rpc' => 'manage rpc server',
        'db' => 'manage database operations',
    ],
    'Arguments:' => [
        'name' => '*description for the argument [name], is required',
        'sex' => 'Description for the argument [sex], is optional',
        'age' => 'Description for the argument [age], is optional',
    ],
    'Options:' =>[
        '-y|--yes' => 'Description for the option [yes], is boolean',
        '-y|--yes' => 'Description for the option [yes], is boolean',
        '-y|--yes' => 'Description for the option [yes], is boolean',
        '-y|--yes' => 'Description for the option [yes], is boolean',
        '-h,--help' => 'show help information',
        '-v,--version' => 'show version information'
    ]
];

$str = "
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;     ______        _____  _____ _____     ;
;    / ___\ \      / / _ \|  ___|_   _|    ;
;    \___ \\ \ /\ / / | | | |_    | |      ;
;     ___) |\ V  V /| |_| |  _|   | |      ;
;    |____/  \_/\_/  \___/|_|     |_|      ;
;                                          ;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
";


\Swoft\Console\Output\Output::writeln($str);
\Swoft\Console\Output\Output::writeList($list, 'comment', 'info');

exit();

$pdo = new PDO("mysql:dbname=test;host=127.0.0.1", "root", "123456");
$stm = $pdo->prepare("select * from count where uid=?");
$stm->execute([1 => 424]);

var_dump($stm->fetchAll());
