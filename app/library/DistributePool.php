<?php

use Phalcon\Di\Injectable;

class DistributePool extends Injectable
{
    /**
     *  Check the queue from Beanstalk and distribute the quote to suppliers
     */
    public function consumeQueue()
    {
        while(($job = $this->queue->peekReady()) !== false)
        {

            $message = $job->getBody();
            var_dump($message);



            $job->delete();

        }
    }
}
