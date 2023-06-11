<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    
    public const PROGRAM = [ ['title' => 'Breaking Bad', 'synopsis' => 'La série se concentre sur Walter White, un professeur de chimie surqualifié et père de famille, qui, ayant appris qu\'il est atteint d\'un cancer du poumon en phase terminale, sombre dans le crime pour assurer l\'avenir financier de sa famille.',
    'category_name' => 'category_Action',  'country' => 'Américaine', 'year' => '2008', 'program_name' => 'Breaking Bad', 'owner' => 'owner_1'],
    ['title' => 'Game of Thrones', 'synopsis' => 'Après un été de dix années, un hiver rigoureux s\'abat sur le Royaume avec la promesse d\'un avenir des plus sombres. Pendant ce temps, complots et rivalités se jouent sur le continent pour s\'emparer du Trône de Fer, le symbole du pouvoir absolu.', 'category_name' => 'category_Action' , 'country' => 'Américaine', 'year' => '2011', 'program_name' => 'Game Of Thrones', 'owner' => 'owner_1'],
    ['title' => 'Arcane', 'synopsis' => 'Championnes de leurs villes jumelles et rivales (la huppée Piltover et la sous-terraine Zaun), deux sœurs Vi et Powder se battent dans une guerre où font rage des technologies magiques et des perspectives diamétralement opposées.', 'category_name' => 'category_Animation', 'country' => 'France', 'year' => '2021', 'program_name' => 'Arcane' , 'owner' => 'owner_1'],
    ['title' => 'Stranger Things', 'synopsis' => 'Quand un jeune garçon disparaît, une petite ville découvre une affaire mystérieuse, des expériences secrètes, des forces surnaturelles terrifiantes... et une fillette.', 'category_name' => 'category_Action',  'country' => 'Américaine', 'year' => '2016', 'program_name' => 'Stranger Things', 'owner' => 'owner_2'],
    ['title' => 'The Mandalorian', 'synopsis' => 'L\'intrigue, située entre la chute de l\'Empire et l\'émergence du Premier Ordre, 
    suit les voyages d\'un chasseur de primes solitaire dans les contrées les plus éloignées de la Galaxie, loin de l\’autorité de la Nouvelle République', 'category_name' => 'category_Aventure' ,  'country' => 'Américaine', 'year' => '2019', 'program_name' => 'The Mandalorian', 'owner' => 'owner_2']
     
];
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAM as $key => $programSeries) {
            $program = new Program();
            $program->setTitle($programSeries['title']);
            $program->setSynopsis($programSeries['synopsis']);
            $program->setCategory($this->getReference($programSeries['category_name']));
            $program->setCountry($programSeries['country']);
            $program->setYear($programSeries['year']);
            $program->setOwner($this->getReference($programSeries['owner']));
            $slug = $this->slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $this->addReference($programSeries['program_name'], $program);
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
            UserFixtures::class
        ];
    }


}
