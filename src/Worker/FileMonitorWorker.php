<?php

declare(strict_types=1);

namespace Luzrain\WorkermanBundle\Worker;

use Luzrain\WorkermanBundle\Reboot\FileMonitorWatcher\FileMonitorWatcher;
use Workerman\Worker;

final class FileMonitorWorker
{
    public const PROCESS_TITLE = '[FileMonitor]';

    public function __construct(string|null $user, string|null $group, array $sourceDir, array $filePattern)
    {
        $worker = new Worker();
        $worker->name = self::PROCESS_TITLE;
        $worker->user = $user ?? '';
        $worker->group = $group ?? '';
        $worker->count = 1;
        $worker->reloadable = false;
        $worker->onWorkerStart = function (Worker $worker) use ($sourceDir, $filePattern): void {
            $worker->log($worker->name . ' started');
            $fileMonitor = FileMonitorWatcher::create($worker, $sourceDir, $filePattern);
            $fileMonitor->start();
        };
    }
}
