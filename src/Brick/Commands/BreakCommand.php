<?php namespace Brick\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Brick\Brick, Brick\Config\ConfigLoader;

class BreakCommand extends Command {

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('break');

        $this->setDescription('Attempts to break the given class');

        $this->setDefinition([
            new InputArgument('class', InputArgument::REQUIRED, 'Full class name'),
        ]);
    }

    /**
     * Execute the command
     *
     * @param  Symfony\Component\Console\Input\InputInterface   $input
     * @param  Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = (new ConfigLoader)->load();

        $report = function($message) use($output)
        {
            $output->writeln($message);
        };

        (new Brick)->run($input->getArgument('class'), $config, $report);
    }

}

