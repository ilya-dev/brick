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

        foreach (range(1, $this->config['attempts']) as $iteration)
        {
            $decision = $strategy->decide($instance);

            if (is_null($decision)) continue;

            $this->report("<info>#{$iteration}:</info> $decision");

            Exceptions::remember();

            $outcome = $this->takeAction($instance, $decision);

            if (Exceptions::wereAdded())
            {
                $this->report("<error>It looks like your code's just got broken!</error>");

                $this->report("<error>".Exceptions::getLastMessage()."</error>");

                return;
            }
            else
            {
                $outcome = Exporter::export($outcome);

                $this->report("<comment># => {$outcome}</comment>");
            }
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
        $this->report(\str_repeat('-', 40));
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

        $this->report(
            "<info>Running {$strategy} strategy {$attempts} times</info>"
        );

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

