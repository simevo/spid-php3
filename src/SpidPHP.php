<?php

namespace SpidPHP;

use SpidPHP\Spid\Interfaces\SpInterface;
 
class SpidPHP
{
    private $protocol;

    public function __construct(SpInterface $protocol)
    {
        $this->protocol = $protocol;
    }

    public function __call($method, $arguments)
    {
        $methods_implemented = get_class_methods($this->protocol);
        if (!in_array($method, $methods_implemented)) {
            throw new \Exception("Invalid method requested", 1);
        }
        call_user_func_array(array($this->protocol, $method), $arguments);
    }
}