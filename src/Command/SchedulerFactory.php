<?php

declare(strict_types=1);
/**
 * @copyright (c) 2017 Stickee Technology Limited
 */

namespace QueueJitsu\Scheduler\Cli\Command;

use Psr\Container\ContainerInterface;
use QueueJitsu\Scheduler\Worker\Worker;

/**
 * Class SchedulerFactory
 *
 * @package QueueJitsu\Scheduler\Cli\Command
 */
class SchedulerFactory
{
    /**
     * __invoke
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return \QueueJitsu\Scheduler\Cli\Command\Scheduler
     */
    public function __invoke(ContainerInterface $container)
    {
        $worker = $container->get(Worker::class);

        return new Scheduler($worker);
    }
}
