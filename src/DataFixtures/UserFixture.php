<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class,10,function(User $user,$i)    use($manager){
            $user->setEmail("spacebar{$i}@gmail.com");
            $user->setFirstName($this->faker->firstName);
            return $user;
        });

        $manager->flush();
    }
}
