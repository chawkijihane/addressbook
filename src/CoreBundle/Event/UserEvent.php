<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{
    /**
     * The USER_EDIT_SUCCESS event occurs when an user is edit completely.
     *
     * The event listener method receives a CoreBundle\Event\UserEvent instance.
     *
     * @var string
     */
    const USER_EDIT_SUCCESS = 'user.edit.success';

    /**
     * The USER_ADD_SUCCESS event occurs when an user is add completely.
     *
     * The event listener method receives a CoreBundle\Event\UserEvent instance.
     *
     * @var string
     */
    const USER_ADD_SUCCESS = 'user.add.success';


    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
