<?php
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
     * @param $name
     * @param callable $callback
     *
     * @return \Symfony\Component\Console\Application
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback)
    {
        /** @var \Symfony\Component\Console\Application $app */
        $app = $callback();

        $command = $container->get(Scheduler::class);

        $app->add($command);

        return $app;
    }
}
