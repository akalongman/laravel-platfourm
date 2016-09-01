<?php

namespace Longman\Platfourm\Repository\Events;

/**
 * Class RepositoryEntityUpdated.
 */
class RepositoryEntityRestored extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = 'restored';
}
