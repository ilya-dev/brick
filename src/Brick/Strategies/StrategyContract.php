<?php namespace Brick\Strategies;

interface StrategyContract {

    /**
     * Make a decision
     *
     * @param  mixed $object
     * @return Brick\Action
     */
    public function decide($object);

}

