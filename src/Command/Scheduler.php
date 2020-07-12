<?php
/*
 * Copyright (c) 2017 - 2020 Martin Meredith
 * Copyright (c) 2017 Stickee Technology Limited
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

declare(strict_types=1);
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
     */
    protected function configure(): void
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
     * @throws \QueueJitsu\Exception\ForkFailureException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     *
     * @return int
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
    private function workInBackground(InputInterface $input): void
    {
        $pid = pcntl_fork();

        if ($pid === -1) {
            die('Could not fork worker');
        }

        if (!$pid) {
            /** @var string $pidfile */
            $pidfile = $input->getOption('pidfile');

            if ($pidfile) {
                $this->writePidFile($pidfile);
            }

            $scheduler = $this->worker;

            /** @var string $interval */
            $interval = $input->getOption('interval');
            $scheduler((int)$interval);
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
    private function workInForeground(InputInterface $input): void
    {
        /** @var string $pidfile */
        $pidfile = $input->getOption('pidfile');

        if ($pidfile) {
            $this->writePidFile($pidfile);
        }

        $scheduler = $this->worker;

        /** @var string $interval */
        $interval = $input->getOption('interval');
        $scheduler((int)$interval);
    }

    /**
     * writePidFile
     *
     * @param string $pidfile
     * @param null|int $pid
     */
    private function writePidFile(string $pidfile, $pid = null): void
    {
        if (is_null($pid)) {
            $pid = getmypid();
        }

        $ret = file_put_contents($pidfile, $pid);

        if (!$ret) {
            die(sprintf('Could not write PID information to %s', $pidfile));
        }
    }
}
