<?php namespace Brick;

class Implementation extends \Eve\Implement {

    /**
     * Handle dynamic static method calls.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method, array $arguments)
    {
        $method = \str_replace('for', 'an', $method);

        return \call_user_func_array([new static, $method], $arguments);
    }

}

