<?php
namespace stelin\b;

class R
{
    public $a;
    public function __construct(string $a)
    {
        $this->a = $a;
    }
}
class A{
    public function run()
    {
        $method = new \ReflectionMethod($this, 'action');
        $reflectionParams = $method->getParameters();
        foreach ($reflectionParams as $reflectionParam) {
            $type = $reflectionParam->getType();
            echo $type;
            var_dump($type == R::class);
        }
//        $method->invokeArgs($this, array(new R('stelin')));
    }
}

class B extends  A{
    public function action(R $request, $a, $b){
        echo $request->a;
    }
}

$a = new B();
$a->run();