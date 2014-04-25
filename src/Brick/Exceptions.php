<?php namespace Brick;

class Exceptions {

    /**
     * The caught exceptions.
     *
     * @var array
     */
    protected $exceptions = [];

    /**
     * The last "remembered" state.
     *
     * @var integer
     */
    protected $remembered = 0;

    /**
     * Retrieve the message string out of the latest caught exception.
     *
     * @return string
     */
    public function getLastMessage()
    {
        $exception = \end($this->exceptions);

        \reset($this->exceptions);

        return $exception->getMessage();
    }

    /**
     * "Remember" the current state.
     *
     * @return void
     */
    public function remember()
    {
        $this->remembered = \count($this->exceptions);
    }

    /**
     * Whether the current state has changed since the last time it was "remembered".
     *
     * @return boolean
     */
    public function wereAdded()
    {
        return \count($this->exceptions) !== $this->remembered;
    }

    /**
     * Add an exception.
     *
     * @param \Exception $exception
     * @return void
     */
    public function add(\Exception $exception)
    {
        $this->exceptions[] = $exception;
    }

}

