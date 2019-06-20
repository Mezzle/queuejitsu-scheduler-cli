<?php

declare(strict_types=1);
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
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return \QueueJitsu\Scheduler\Adapter\RedisAdapter
     */
    public function __invoke(ContainerInterface $container)
    {
        $client = $container->get(Client::class);

        return new RedisAdapter($client);
    }
}
