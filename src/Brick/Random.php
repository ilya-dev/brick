<?php namespace Brick;

class Random {

    /**
     * Call a method with random arguments list.
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
     * Create an instance of an existing class or build an implementation.
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
     * Get a random value.
     *
     * @return mixed
     */
    public static function value()
    {
        $methods = [
            'integer' , 'float'  , 'boolean',
            'array'   , 'null'   , 'closure',
            'object'  , 'string' ,
        ];

        $method = 'get'.\ucfirst($methods[\array_rand($methods)]);

        return \call_user_func([__CLASS__, $method]);
    }

    /**
     * Get null.
     *
     * @return null
     */
    public static function getNull()
    {
        return null;
    }

    /**
     * Get an empty closure.
     *
     * @return \Closure
     */
    public static function getClosure()
    {
        return function() {};
    }

    /**
     * Get a random boolean.
     *
     * @return boolean
     */
    public static function getBoolean()
    {
        return (boolean)(\rand() & 1);
    }

    /**
     * Get a random integer.
     *
     * @return integer
     */
    public static function getInteger()
    {
        return \rand();
    }

    /**
     * Get a new instance of \stdClass.
     *
     * @return \stdClass
     */
    public static function getObject()
    {
        return new \stdClass;
    }

    /**
     * Get a random float.
     *
     * @return float
     */
    public static function getFloat()
    {
        return \floatval(\rand().'.'.\rand());
    }

    /**
     * Get a random string.
     *
     * @return string
     */
    public static function getString()
    {
        return \uniqid('', true);
    }

    /**
     * Get a random array.
     *
     * @return array
     */
    public static function getArray()
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

