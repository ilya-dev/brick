<?php namespace Brick\Strategies;

interface StrategyContract {

    /**
     * Take action.
     *
     * @param mixed $object
     * @return \Brick\Action|null
     */
    public function decide($object);

}

