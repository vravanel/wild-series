<?php

namespace App\Controller;

use App\DataFixtures\EpisodeFixtures;
use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;

class ProgramController extends AbstractController
{
    #[Route('/program', name: 'program_index')]
public function index(ProgramRepository $programRepository): Response
{
    $programs = $programRepository->findAll();
    return $this->render(
        'program/index.html.twig',
        ['programs' => $programs]
    );
}

#[Route('/show/{id<^[0-9]+$>}', name: 'program_show')]
public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
{
    $program = $programRepository->findOneBy(['id' => $id]);
    $seasons = $seasonRepository->findBy(
        ['program' => $program], ['id' => 'ASC']
    );

    if (!$program) {
        throw $this->createNotFoundException(
            'No program with id : '.$id.' found in program\'s table.'
        );
    }
    return $this->render('program/show.html.twig', [
        'program' => $program,
        'seasons' => $seasons,
    ]);
}

#[Route('/program/{programId}/season/{seasonId}', name: 'program_season_show')] 
public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository) 
{
        $program = $programRepository->findOneBy(['id'=>$programId]);
        $season = $seasonRepository->find($seasonId);

    if (!$program ) {
        throw $this->createNotFoundException(
            'No program with id : '.$programId.' found in program\'s table.'
        );
    }
    return $this->render('program/season_show.html.twig', [
        'program' => $program,
        'season' => $season,
    ]);

}

}
