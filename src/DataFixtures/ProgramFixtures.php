<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    
    const PROGRAM = [ ['title' => 'Breaking Bad', 'synopsys' => 'La série se concentre sur Walter White, un professeur de chimie surqualifié et père de famille, qui, ayant appris qu\'il est atteint d\'un cancer du poumon en phase terminale, sombre dans le crime pour assurer l\'avenir financier de sa famille.',
    'category_name' => 'category_Action'], 
    ['title' => 'Game of Thrones', 'synopsys' => 'Après un été de dix années, un hiver rigoureux s\'abat sur le Royaume avec la promesse d\'un avenir des plus sombres. Pendant ce temps, complots et rivalités se jouent sur le continent pour s\'emparer du Trône de Fer, le symbole du pouvoir absolu.', 'category_name' => 'category_Action'],
    ['title' => 'Arcane', 'synopsys' => 'Championnes de leurs villes jumelles et rivales (la huppée Piltover et la sous-terraine Zaun), deux sœurs Vi et Powder se battent dans une guerre où font rage des technologies magiques et des perspectives diamétralement opposées.', 'category_name' => 'category_Animation' ],
    ['title' => 'Stranger Things', 'synopsys' => 'Quand un jeune garçon disparaît, une petite ville découvre une affaire mystérieuse, des expériences secrètes, des forces surnaturelles terrifiantes... et une fillette.', 'category_name' => 'category_Action'],
    ['title' => 'The Mandalorian', 'synopsys' => 'Après les aventures de Jango et Boba Fett, un nouveau héros émerge dans l\'univers Star Wars. L\'intrigue, située entre la chute de l\'Empire et l\'émergence du Premier Ordre, 
    suit les voyages d\'un chasseur de primes solitaire dans les contrées les plus éloignées de la Galaxie, loin de l\’autorité de la Nouvelle République', 'category_name' => 'category_Aventure']
     
];


    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAM as $key => $programSeries) {
            $program = new Program();
            $program->setTitle($programSeries['title']);
            $program->setSynopsis($programSeries['synopsys']);
            $program->setCategory($this->getReference($programSeries['category_name']));
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
        ];
    }


}
