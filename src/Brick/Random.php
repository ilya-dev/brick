<?php namespace Brick;

class Random {

    /**
     * Call a method with random arguments
     *
     * @return array
     */
    public static function arguments(\ReflectionMethod $method)
    {
        $arguments = [];

        foreach ($method->getParameters() as $argument)
        {
            if ( ! \is_null($class = $argument->getClass()))
            {
                $arguments[] = static::resolveClass($class);
            }
            else
            {
                $arguments[] = static::value();
            }
        }

        return $arguments;
    }

    /**
     * Create an instance of existing class or build an implementation
     *
     * @return mixed
     */
    protected static function resolveClass(\ReflectionClass $class)
    {
        $name = $class->getName();

        if (\class_exists($name, true) && ! $class->isAbstract())
        {
            return Creator::create($name);
        }

        if ($class->isInterface())
        {
            $implementation = Implementation::forInterface($name);
        }
        else
        {
            $implementation = Implementation::forAbstract($name);
        }

        return new $implementation;
    }

    /**
     * Get random value
     *
     * @return mixed
     */
    public static function value()
    {
        $methods = [
            '_integer' , '_float'  , '_boolean',
            '_array'   , '_null'   , '_closure',
            '_object'  , '_string' ,
        ];

        $method = $methods[\array_rand($methods)];

        return \call_user_func([__CLASS__, $method]);
    }

    public static function _null()
    {
        return null;
    }

    public static function _closure()
    {
        return function() {};
    }

    public static function _boolean()
    {
        return (boolean)(\rand() & 1);
    }

    public static function _integer()
    {
        return \rand();
    }

    public static function _object()
    {
        // rewriting is in order
        return new \stdClass;
    }

    public static function _float()
    {
        return \floatval(\rand().'.'.\rand());
    }

    public static function _string()
    {
        return \uniqid("", true);
    }

    public static function _array()
    {
        $values = [];

        foreach (\range(1, \rand(1, 10)) as $index)
        {
            if (\is_object($value = static::value()) or \is_array($value))
            {
                continue;
            }

            $values[] = $value;
        }

        return $values;
    }

}

