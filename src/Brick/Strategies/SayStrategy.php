<?php namespace Brick\Strategies;

class SayStrategy extends Strategy {

    /**
     * {@inheritdoc}
     */
    public function decide($object)
    {
        $reflector = new \ReflectionClass($object);

        $methods = [];

        foreach ($reflector->getMethods() as $method)
        {
            if (\count($method->getParameters()) === 0) continue;

            $methods[] = $method;
        }

        if (\count($methods) === 0) return null;

        $method = $methods[\array_rand($methods)];

        return new \Brick\Action(
            $method->getName(), \Brick\Random::arguments($method)
        );
    }

}

