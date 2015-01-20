<?php

/**
 *  This script distributes quotes to suppliers
 */

require 'cli-bootstrap.php';

class DistributeQuoteTask extends Phalcon\DI\Injectable
{
    public function run()
    {
        $spool = new DistributePool();
        $spool->consumeQueue();
    }
}

try {
    $task = new DistributeQuoteTask($config);
    $task->run();
} catch(Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString();
}
