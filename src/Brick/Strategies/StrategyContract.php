<?php namespace Brick\Strategies;

interface StrategyContract {

    /**
     * Make a decision
     *
     * @param  mixed $object
     * @return Brick\Action|null
     */
    public function decide($object);

}

