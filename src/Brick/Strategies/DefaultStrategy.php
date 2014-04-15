<?php namespace Brick\Strategies;

class DefaultStrategy extends Strategy {

    /**
     * {@inheritdoc}
     */
    public function decide($object)
    {
        $strategy = $this->pickStrategy();

        return $this->call($strategy, $object);
    }

    /**
     * Pick a random strategy
     *
     * @return string
     */
    protected function pickStrategy()
    {
        $strategies = ['ask', 'say'];

        return $strategies[\array_rand($strategies)];
    }

}

