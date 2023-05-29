<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $numberOfSeason = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        
        foreach (ProgramFixtures::PROGRAM as $programName) {    
            for ($i = 1; $i <= 5; $i++) {
                self::$numberOfSeason++;
            $season = new Season(); 
                $season->setNumber($i);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));
                $this->setReference('season_' . self::$numberOfSeason, $season);

                $season->setProgram($this->getReference($programName['program_name']));
                $manager->persist($season);
                
            }
        }

        $manager->flush();
    }

     public function getDependencies()
    {
        
        return [
          ProgramFixtures::class,
        ];
    }
}
