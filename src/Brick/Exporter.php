<?php namespace Brick;

class Exporter {

    /**
     * Better var_export
     *
     * @param  mixed  $thing
     * @return string
     */
    public static function export($thing)
    {
        if (\is_object($thing))
        {
            return static::exportObject($thing);
        }

        if (\is_array($thing))
        {
            return static::exportArray($thing);
        }

        return \var_export($thing, true);
    }

    /**
     * Export an object
     *
     * @param  mixed  $object
     * @return string
     */
    protected static function exportObject($object)
    {
        return 'instance of '.\get_class($object);
    }

    /**
     * Export an array
     *
     * @param  array  $array
     * @return string
     */
    protected static function exportArray(array $array)
    {
        $string = \str_replace('array (', '[', \var_export($array, true));
        $string = \substr($string, 0, \strlen($string) - 1).']';

        return $string;
    }

}

