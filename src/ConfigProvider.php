<?php
/**
 * @copyright (c) 2017 Stickee Technology Limited
 */

namespace QueueJitsu\Scheduler\Cli;

use QueueJitsu\Scheduler\Cli\Application\ApplicationDelegator;
use Symfony\Component\Console\Application;

/**
 * Class ConfigProvider
 *
 * @package QueueJitsu\Scheduler\Cli
 */
class ConfigProvider
{
    /**
     * __invoke
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * getDependencies
     *
     * @return array
     */
    private function getDependencies(): array
    {
        return [
            'factories' => [
                Command\Scheduler::class => Command\SchedulerFactory::class,
            ],
            'delegators' => [
                Application::class => [
                    ApplicationDelegator::class
                ]
            ]
        ];
    }
}
