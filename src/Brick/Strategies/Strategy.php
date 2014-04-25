<?php namespace Brick\Strategies;

abstract class Strategy implements StrategyContract {

    /**
     * {@inheritdoc}
     */
    abstract public function decide($object);

    /**
     * Run a strategy.
     *
     * @param string $strategy
     * @param mixed $object
     * @return \Brick\Action
     */
    protected function call($strategy, $object)
    {
        $strategy = \sprintf('Brick\Strategies\%sStrategy', \ucfirst($strategy));

        return (new $strategy)->decide($object);
    }

}

