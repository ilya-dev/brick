<?php namespace Brick;

final class Brick {

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
        $this->class  = str_replace('/', '\\', $class);

        $this->writeHead();

        $this->registerHandlers();

        $this->doRun();

        $this->finish();
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
        $logger   = Creator::create($this->config['logger']);

        foreach (range(1, $this->config['attempts']) as $iteration)
        {
            $decision = $strategy->decide($instance);

            if (is_null($decision)) continue;

            $this->report("<info>#{$iteration}:</info> $decision");

            // print the result
            // if it failed with an exception, stop execution and tell
            // what actually happened
            // don't forget about logging!
        }
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
            Exceptions::add($exception);
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
     * Finish execution
     *
     * @return void
     */
    protected function finish()
    {
        $this->report("<info>Done</info>");
    }

    /**
     * Write header information
     *
     * @return void
     */
    protected function writeHead()
    {
        $this->report(
            "<info>Attempting to break <comment>{$this->class}</comment> class</info>"
        );

        $strategy = "<comment>{$this->config['strategy']}</comment>";
        $attempts = "<comment>{$this->config['attempts']}</comment>";
        $logger   = "<comment>{$this->config['logger']}</comment>";

        $this->report(
            "<info>Running {$strategy} strategy {$attempts} times</info>"
        );

        $this->report("<info>Logging using {$logger} logger</info>");
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

