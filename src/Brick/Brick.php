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
     * @var Closure
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
     * @param  string  $class
     * @param  array   $config
     * @param  Closure $report
     * @return void
     */
    public function run($class, array $config, \Closure $report)
    {
        $this->config = $config;
        $this->report = $report;
        $this->class  = $class;

        $this->writeHead();
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

