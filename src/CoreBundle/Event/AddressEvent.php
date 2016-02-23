<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\Email;
use Symfony\Component\EventDispatcher\Event;

class AddressEvent extends Event
{
    /**
     * The ADDRESS_ADD_SUCCESS event occurs when an order is confirmed completely.
     *
     * The event listener method receives a CoreBundle\Event\OrderEvent instance.
     *
     * @var string
     */
    const ADDRESS_ADD_SUCCESS = 'address.add.success';

    /**
     * The address.delete.failure event occurs when an error occurs while deleting an order.
     *
     * The event listener method receives a Symfony\Component\EventDispatcher\GenericEvent instance.
     *
     * @var string
     */
    const ADDRESS_DELETE_COMPLETED = 'address.delete.failure';

    /**
     * The address.delete.success event occurs when an order is deleted successfully.
     *
     * The event listener method receives a Symfony\Component\EventDispatcher\GenericEvent instance.
     *
     * @var string
     */
    const ADDRESS_DELETE_SUCCESS = 'address.delete.success';

    /**
     * The ADDRESS_EDIT_COMPLETED event occurs when an order is edited completely.
     *
     * The event listener method receives a BW\CoreBundle\Event\UserEvent instance.
     *
     * @var string
     */
    const ADDRESS_EDIT_COMPLETED = 'address.edit.completed';

    /**
     * The ADDRESS_EDIT_SUCCESS event occurs when an order is confirmed completely.
     *
     * The event listener method receives a CoreBundle\Event\OrderEvent instance.
     *
     * @var string
     */
    const ADDRESS_EDIT_SUCCESS = 'address.edit.success';

    /**
     * The ADDRESS_INVITE_COMPLETED event occurs when an order is edited completely.
     *
     * The event listener method receives a BW\CoreBundle\Event\UserEvent instance.
     *
     * @var string
     */
    const ADDRESS_INVITE_COMPLETED = 'address.invite.completed';

    /**
     * The ADDRESS_INVITE_SUCCESS event occurs when an order is confirmed completely.
     *
     * The event listener method receives a CoreBundle\Event\OrderEvent instance.
     *
     * @var string
     */
    const ADDRESS_INVITE_SUCCESS = 'address.invite.success';

    private $address;

    public function __construct(Email $address)
    {
        $this->address = $address;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}
