<?php

/**
 * DummyServer
 *
 * This classs replaces Beanstalkd by a dummy server
 */
class DummyServer
{
    /**
     * Simulates putting a job in the queue
     *
     * @param array $job
     */
    public function put($job)
    {
        return true;
    }
}
