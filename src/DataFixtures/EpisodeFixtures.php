<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const NUM_EPISODE = 10;
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($s = 1; $s <= SeasonFixtures::$numberOfSeason; $s++) {
            for($i = 1; $i <= self::NUM_EPISODE; $i++) {
                $episode = new Episode();
                $episode->setTitle($faker->word())->setSynopsis($faker->paragraph(2))->setNumber($i);
                $episode->setSeason($this->getReference('season_' . $s));
                $manager->persist($episode);
            }
            $manager->flush();
        }

    }


    public function getDependencies()
    {
        
        return [
          SeasonFixtures::class,
        ];
    }

}