<?php
/**
 * @copyright (c) 2017 Stickee Technology Limited
 */

namespace QueueJitsu\Scheduler\Cli\Command;

use QueueJitsu\Scheduler\Worker\Worker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Scheduler extends Command
{
    /**
     * @var \QueueJitsu\Scheduler\Worker\Worker $worker
     */
    private $worker;

    /**
     * Scheduler constructor.
     *
     * @param \QueueJitsu\Scheduler\Worker\Worker $worker
     *
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(Worker $worker)
    {
        parent::__construct('scheduler:run');
        $this->worker = $worker;
    }

    /**
     * configure
     *
     */
    protected function configure()
    {
        $this->setDescription('Runs Scheduler');
        $this->setHelp('This command starts a worker to run the scheduling;');

        $this->addOption(
            'background',
            'b',
            InputOption::VALUE_NONE,
            'Run in the background'
        );

        $this->addOption(
            'interval',
            'i',
            InputOption::VALUE_REQUIRED,
            'How long to wait before checking for new jobs when none are available (in seconds)',
            5
        );

        $this->addOption(
            'pidfile',
            null,
            InputOption::VALUE_REQUIRED,
            'Location of Pidfile'
        );
    }

    /**
     * execute
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     * @throws \QueueJitsu\Exception\ForkFailureException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('background')) {
            $this->workInBackground($input);

            return 0;
        }

        $this->workInForeground($input);

        return 0;
    }

    /**
     * workInBackground
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \QueueJitsu\Exception\ForkFailureException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    private function workInBackground(InputInterface $input)
    {
        $pid = pcntl_fork();

        if ($pid === -1) {
            die('Could not fork worker');
        }

        if (!$pid) {
            $pidfile = $input->getOption('pidfile');
            if ($pidfile) {
                $this->writePidFile($pidfile);
            }

            $scheduler = $this->worker;

            $scheduler($input->getOption('interval'));
        }
    }

    /**
     * workInForeground
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \QueueJitsu\Exception\ForkFailureException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    private function workInForeground(InputInterface $input)
    {
        $pidfile = $input->getOption('pidfile');
        if ($pidfile) {
            $this->writePidFile($pidfile);
        }

        $scheduler = $this->worker;

        $scheduler($input->getOption('interval'));
    }

    /**
     * writePidFile
     *
     * @param string $pidfile
     * @param null|int $pid
     */
    private function writePidFile(string $pidfile, $pid = null)
    {
        if (is_null($pid)) {
            $pid = getmypid();
        }

        file_put_contents($pidfile, $pid) || die(sprintf('Could not write PID information to %s', $pidfile));
    }
}
