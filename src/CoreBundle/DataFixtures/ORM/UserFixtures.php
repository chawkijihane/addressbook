<?php

namespace CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CoreBundle\Entity\User;
use Symfony\Component\Finder\Finder;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
    }
}
