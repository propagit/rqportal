<?php

use Phalcon\Acl;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * Permission
 *
 * Prevents User Types from accessing areas they are not allowed in.
 */
class Permission extends Plugin
{
    /**
     * Constants to prevent a typo
     */
    const GUEST = 'guest';
    const APPLICANT = 'applicant';
    const USER = 'user';
    const ADMIN = 'admin';

    /**
     * Accessible to everyone
     */

}
