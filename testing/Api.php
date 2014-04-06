<?php namespace Testing;

class Api {

    public function isApi()
    {
        return true;
    }

    public function doSomething($thing)
    {
        return \strlen($thing) & 1;
    }

    public function makeWorldBetter(CrazyInterface $bar)
    {
        return $bar->wow();
    }

}

interface CrazyInterface {

    public function bar($such = null, $rly = 42);

}

