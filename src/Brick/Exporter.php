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
        if (is_object($thing)) return "instance of ".\get_class($thing);

        $thing = \var_export($thing, true);

        // TODO: make it prettier
        if (\strpos($thing, 'array (') !== false)
        {
            // array () => []
            $thing = \str_replace('array (', '[', $thing);
            $thing = \substr($thing, 0, \strlen($thing) - 1).']';
        }

        return $thing;
    }

}

