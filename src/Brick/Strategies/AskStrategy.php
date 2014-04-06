<?php namespace Brick\Strategies;

class AskStrategy extends Strategy {

    /**
     * {@inheritdoc}
     */
    public function decide($object)
    {
        $reflector = new \ReflectionClass($object);

        $methods = [];

        foreach ($reflector->getMethods() as $method)
        {
            if (\count($method->getParameters()) === 0)
            {
                $methods[] = $method->getName();
            }
        }

        if (\count($methods) === 0) return null;

        return new \Brick\Action($methods[\array_rand($methods)], []);
    }

}

