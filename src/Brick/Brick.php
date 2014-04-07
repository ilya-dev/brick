<?php namespace Brick;

class Brick {

    /**
     * Brick configuration
     *
     * @var array
     */
    protected $config;

    /**
     * Bridge to Symfony's OutputInterface implementation
     *
     * @var \Closure
     */
    protected $report;

    /**
     * Class we attempt to break
     *
     * @var string
     */
    protected $class;

    /**
     * Exceptions instance
     *
     * @var \Brick\Exceptions
     */
    protected $exceptions;

    /**
     * The constructor
     *
     * @param  \Brick\Exceptions $exceptions
     * @return void
     */
    public function __construct(Exceptions $exceptions)
    {
        $this->exceptions = $exceptions;
    }

    /**
     * Run Brick
     *
     * @param  string   $class
     * @param  array    $config
     * @param  \Closure $report
     * @return void
     */
    public function run($class, array $config, \Closure $report)
    {
        $this->config = $config;
        $this->report = $report;

        // so you can write My/Class instead of "My\Class"
        $this->setClass($class);

        $this->registerHandlers();
        $this->start();
        $this->doRun();
    }

    /**
     * Set the class name
     *
     * @param  string $class
     * @return void
     */
    protected function setClass($class)
    {
        $this->class = str_replace('/', '\\', $class);
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

            if (is_null($action))
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
     * Report an action
     *
     * @param  \Brick\Action  $action
     * @param  integer        $index
     * @return void
     */
    protected function reportAction(Action $action, $index)
    {
        $this->report("<info>#{$index}:</info> $action");
    }

    /**
     * Report a result
     *
     * @param  mixed $result
     * @return void
     */
    protected function reportResult($result)
    {
        $result = Exporter::export($result);

        $this->report("<comment># => {$result}</comment>");
    }

    /**
     * Report broken code
     *
     * @return void
     */
    protected function reportBroken()
    {
        $this->report("<error>It looks like your code's just got broken!</error>");

        $this->report("<error>".$this->exceptions->getLastMessage()."</error>");
    }

    /**
     * Invoke a method with given set of arguments
     *
     * @param  mixed         $instance
     * @param  \Brick\Action $action
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
     * Register custom exception and error handlers
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
     * Write header information
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
     * Report
     *
     * @param  string $message
     * @return void
     */
    protected function report($message)
    {
        $report = $this->report;

        $report($message);
    }

}

