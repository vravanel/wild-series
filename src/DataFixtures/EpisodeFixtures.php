<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const NUM_EPISODE = 10;
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($s = 1; $s <= SeasonFixtures::$numberOfSeason; $s++) {
            for($i = 1; $i <= self::NUM_EPISODE; $i++) {
                $episode = new Episode();
                $episode->setTitle($faker->word())->setSynopsis($faker->paragraph(2))->setNumber($i);
                $episode->setDuration($faker->numberBetween(20, 50));
                $episode->setSeason($this->getReference('season_' . $s));
                $episode->setDuration($faker->numberBetween(30, 75));
                $slug = $this->slugger->slug($episode->getTitle());
                $episode->setSlug($slug);
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