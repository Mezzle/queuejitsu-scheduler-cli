<?php

declare(strict_types=1);
/**
 * @copyright (c) 2017 Stickee Technology Limited
 */

namespace QueueJitsu\Scheduler\Cli\Application;

use Psr\Container\ContainerInterface;
use QueueJitsu\Scheduler\Cli\Command\Scheduler;

/**
 * Class ApplicationDelegator
 *
 * @package QueueJitsu\Scheduler\Cli\Application
 */
class ApplicationDelegator
{
    /**
     * __invoke
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param string $name
     * @param callable $callback
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Console\Exception\LogicException
     *
     * @return \Symfony\Component\Console\Application
     */
    public function __invoke(ContainerInterface $container, string $name, callable $callback)
    {
        /** @var \Symfony\Component\Console\Application $app */
        $app = $callback();

        $command = $container->get(Scheduler::class);

        $app->add($command);

        return $app;
    }
}
