<?php namespace Testing;

class Api {

    public function getData()
    {
        return [
            'status'  => true,

            'version' => 2.29
        ];
    }

    public function doSomething($thing)
    {
        return \strlen($thing) & 1;
    }

    public function makeWorldBetter(CrazyInterface $bar)
    {
        return $bar->wow();
    }

    public function makeCoffee()
    {
        return new \stdClass;
    }

}

interface CrazyInterface {

    public function bar($such = null, $rly = 42);

    public function wow();

}

