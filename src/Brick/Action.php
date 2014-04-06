<?php namespace Brick;

class Action {

    /**
     * Action's unique indentificator
     *
     * @var string
     */
    protected $uid;

    /**
     * Method called
     *
     * @var string
     */
    protected $method;

    /**
     * Arguments passed
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The constructor
     *
     * @param  string $method
     * @param  array  $arguments
     * @return void
     */
    public function __construct($method, array $arguments)
    {
        $this->uid       = \spl_object_hash($this);
        $this->method    = $method;
        $this->arguments = $arguments;
    }

    /**
     * Get the method name
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the array of arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

}

