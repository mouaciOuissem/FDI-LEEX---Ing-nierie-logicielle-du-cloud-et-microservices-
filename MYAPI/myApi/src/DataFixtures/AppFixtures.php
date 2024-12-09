<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager ): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create('fr_FR');

        //encoder microservice credentials
        $encoder = new User();
        $encoder->setUsername("encoder")
             ->setPseudo("encoder")
             ->setEmail("encoder@gmail.com")
             ->setCreatedAt(new DateTime())
             ->setRoles(["ROLE_ADMIN"])
             ->setPassword($this->passwordHasher->hashPassword( $encoder, "encoder" ));
            $manager->persist($encoder);
            $manager->flush();

        // users credentials
        for($i=0; $i<10; $i++){
            $user = new User();
            $firstname = $faker->firstName;
            $user->setUsername($firstname)
                 ->setPseudo($faker->userName)
                 ->setEmail($firstname."@gmail.com")
                 ->setCreatedAt(new DateTime())
                 ->setRoles(["ROLE_USER"])
                 ->setPassword($this->passwordHasher->hashPassword( $user, $firstname ));
                
                $manager->persist($user);
        } 
        $manager->flush();
    }
}
