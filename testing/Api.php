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

}

