<?php
/**
 * @copyright (c) 2017 Stickee Technology Limited
 */

namespace QueueJitsu\Scheduler\Cli\Scheduler;

use Predis\Client;
use Psr\Container\ContainerInterface;
use QueueJitsu\Scheduler\Adapter\RedisAdapter;

class RedisAdapterFactory
{
    /**
     * __invoke
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return \QueueJitsu\Scheduler\Adapter\RedisAdapter
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        $client = $container->get(Client::class);
        return new RedisAdapter($client);
    }
}
