<?php namespace Brick;

class Exceptions {

    /**
     * Array of caught exceptions
     *
     * @var array
     */
    protected static $exceptions = [];

    /**
     * Last remembered state
     *
     * @var integer
     */
    protected static $remembered = 0;

    /**
     * Get a message string from the latest caught exception
     *
     * @return string
     */
    public static function getLastMessage()
    {
        $exception = \end(static::$exceptions);

        \reset(static::$exceptions);

        return $exception->getMessage();
    }

    /**
     * "Remember" current state
     *
     * @return void
     */
    public static function remember()
    {
        static::$remembered = \count(static::$exceptions);
    }

    /**
     * Whether the current state has changed since the last time it was "remembered"
     *
     * @return boolean
     */
    public static function wereAdded()
    {
        return \count(static::$exceptions) !== static::$remembered;
    }

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

