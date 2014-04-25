<?php namespace Brick;

class Brick {

    /**
     * The configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The bridge to OutputInterface.
     *
     * @var \Closure
     */
    protected $report;

    /**
     * The class that we attempt to break.
     *
     * @var string
     */
    protected $class;

    /**
     * The Exceptions instance.
     *
     * @var Exceptions
     */
    protected $exceptions;

    /**
     * The constructor.
     *
     * @param Exceptions|null $exceptions
     * @return Brick
     */
    public function __construct(Exceptions $exceptions = null)
    {
        $this->exceptions = $exceptions ?: new Exceptions;
    }

    /**
     * Run Brick.
     *
     * @param string $class
     * @param array $config
     * @param \Closure $report
     * @return void
     */
    public function run($class, array $config, \Closure $report)
    {
        $this->config = $config;
        $this->report = $report;

        $this->setClass($class);

        $this->registerHandlers();
        $this->start();
        $this->doRun();
    }

    /**
     * Set the class name.
     *
     * @param string $class
     * @return void
     */
    protected function setClass($class)
    {
        $this->class = \str_replace('/', '\\', $class);
    }

    /**
     * Actually run Brick
     *
     * @return void
     */
    protected function doRun()
    {
        $instance = Creator::create($this->class);
        $strategy = Creator::create($this->config['strategy']);
        $indexes  = \range(1, $this->config['attempts']);

        foreach ($indexes as $index)
        {
            $action = $strategy->decide($instance);

            if (\is_null($action))
            {
                continue;
            }

            $this->reportAction($action, $index);

            $this->exceptions->remember();

            $result = $this->takeAction($instance, $action);

            if ($this->exceptions->wereAdded())
            {
                return $this->reportBroken();
            }
            else
            {
                $this->reportResult($result);
            }
        }
    }

    /**
     * Report an action.
     *
     * @param Action $action
     * @param integer $index
     * @return void
     */
    protected function reportAction(Action $action, $index)
    {
        $this->report("<info>#{$index}:</info> $action");
    }

    /**
     * Report the result.
     *
     * @param mixed $result
     * @return void
     */
    protected function reportResult($result)
    {
        $result = Exporter::export($result);

        $this->report("<comment># => {$result}</comment>");
    }

    /**
     * Report a broken code.
     *
     * @return void
     */
    protected function reportBroken()
    {
        $this->report("<error>It looks like your code's just got broken!</error>");

        $this->report("<error>".$this->exceptions->getLastMessage()."</error>");
    }

    /**
     * Invoke a method with the given set of arguments.
     *
     * @param mixed $instance
     * @param Action $action
     * @return mixed
     */
    protected function takeAction($instance, Action $action)
    {
        try
        {
            $callable = [$instance, $action->getMethod()];

            return \call_user_func_array($callable, $action->getArguments());
        }

        catch(\Exception $exception)
        {
            $this->exceptions->add($exception);
        }
    }

    /**
     * Register the custom error handler.
     *
     * @return void
     */
    protected function registerHandlers()
    {
        \set_error_handler(function($severity, $message)
        {
            throw new \RuntimeException($message, $severity);
        });
    }

    /**
     * Write the header information.
     *
     * @return void
     */
    protected function start()
    {
        $class = $this->class;

        list($strategy, $attempts) = [$this->config['strategy'], $this->config['attempts']];

        $this->report("<info>Attempting to break <comment>{$class}</comment> class</info>");
        $this->report("<info>Running <comment>{$strategy}</comment> strategy <comment>{$attempts}</comment> times</info>");
        $this->report(\str_repeat('-', 40));
    }

    /**
     * Report a message.
     *
     * @param string $message
     * @return void
     */
    protected function report($message)
    {
        $report = $this->report;

        $report($message);
    }

}

