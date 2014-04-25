<?php namespace Brick;

class Action {

    /**
     * The method called.
     *
     * @var string
     */
    protected $method;

    /**
     * The arguments passed.
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The constructor.
     *
     * @param string $method
     * @param array $arguments
     * @return Action
     */
    public function __construct($method, array $arguments)
    {
        $this->method    = $method;
        $this->arguments = $arguments;
    }

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Convert the object to a string.
     *
     * @return string
     */
    public function __toString()
    {
        $arguments = \implode(", ", $this->transformArguments());

        return "\$instance->{$this->method}({$arguments})";
    }

    /**
     * Represent each argument in string format.
     *
     * @return array
     */
    protected function transformArguments()
    {
        $arguments = [];

        foreach ($this->arguments as $argument)
        {
            if (\is_object($argument))
            {
                $arguments[] = 'new \\'.get_class($argument);
            }
            else
            {
                $arguments[] = Exporter::export($argument);
            }
        }

        return $arguments;
    }

}

