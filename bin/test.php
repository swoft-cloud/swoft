<?php
// 递归遍历目录里面的文件
$dir_iterator = new \RecursiveDirectoryIterator(dirname(__FILE__));
$iterator = new \RecursiveIteratorIterator($dir_iterator);
foreach ($iterator as $file){
    // 只监控php文件
    if (pathinfo($file, PATHINFO_EXTENSION) != 'php') {
        echo $file."\n";
        continue;
    }
}

exit();

$reg = '/^.*\\\(\w+)Controller$/';
$result = preg_match($reg, 'app\controllers\DemoController', $match);

var_dump($result, $match);
    exit();
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once __DIR__. '/../vendor/autoload.php';
require_once __DIR__. '/../app/config/define.php';
require_once __DIR__. '/../app/config/model.php';

$config = require dirname(__DIR__). '/app/config/base.php';


$dir = dirname(__FILE__, 2)."/src";
//AnnotationRegistry::registerAutoloadNamespace("swoft\di\annotation", $dir."/di/annotation");
AnnotationRegistry::registerLoader(function($class) use($dir){
    $class = str_replace("swoft\\","", $class);
    $file = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
    $file = $dir."/".$file;
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

$reader = new AnnotationReader();

$files = scanPhpFile($dir, '\swoft');

$s = microtime(true);
foreach ($files as $file){
    $reflClass = new \ReflectionClass($file);
    $classAnnotations = $reader->getClassAnnotations($reflClass);

    var_dump($classAnnotations);
}
$e = microtime(true);
echo $e-$s;

$file = $files[0];
$reflClass = new \ReflectionClass($file);
$classAnnotations = $reader->getClassAnnotations($reflClass);


foreach ($reflClass->getProperties() as $property) {
    if ($property->isStatic()) {
        continue;
    }

    $ret = $reader->getPropertyAnnotations($property);
//    var_dump($property->getName(), $ret);

    foreach ($ret as $pro){
        if($pro instanceof \swoft\di\annotation\Inject && $pro->getName() == ""){
            $reader = new \PhpDocReader\PhpDocReader();

            $name = $property->getName();
            // Read a property type (@var phpdoc)
            $property = new ReflectionProperty($file, $name);
            $propertyClass = $reader->getPropertyClass($property);
            var_dump($file, $name, $propertyClass);
        }
    }
}



var_dump($classAnnotations);
function scanPhpFile(string $dir, $namespace)
{
    $phpFiles = [];

    $files = scandir($dir);
    foreach ($files as $file){
        if($file != '.' && $file != '..' && is_dir($dir."/".$file)){
            $phpFiles = array_merge($phpFiles, scanPhpFile($dir."/".$file, $namespace."\\".$file));
        }elseif(strpos($file, '.php') !== false){
            $file = str_replace(".php", "", $file);
            $phpFiles[] = $namespace."\\".$file;
        }
    }

    return $phpFiles;
}