<?php namespace Brick;

class Exceptions {

    /**
     * Array of caught exceptions
     *
     * @var array
     */
    protected static $exceptions = [];

    /**
     * Add an exception
     *
     * @param  Exception $exception
     * @return void
     */
    public static function add(\Exception $exception)
    {
        static::$exceptions[] = $exception;
    }

    /**
     * Get the amount of caught exceptions
     *
     * @return integer
     */
    public static function count()
    {
        return \count(static::$exceptions);
    }

    /**
     * Get the array of caught exceptions
     *
     * @return array
     */
    public static function all()
    {
        return static::$exceptions;
    }

}

