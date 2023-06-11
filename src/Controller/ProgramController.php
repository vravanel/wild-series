<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Service\ProgramDuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/program/new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository,SluggerInterface $slugger ): Response
    {
        $program = new Program();
        $slug = $slugger->slug($program->getTitle());
        $program->setSlug($slug);
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $program->setOwner($this->getUser());
            $programRepository->save($program, true);

            return $this->redirectToRoute('program_index');
        }

        // Render the form
        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/program/{slug}', name: 'program_show')]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        if (!$program){
            throw $this->createNotFoundException(
                'No program found in programs table.'
            );
        }
        return $this->render('program/show.html.twig',
            ['program' => $program,
                'programDuration' => $programDuration->calculate($program),
            ]);
    }

    #[Route('/{program_slug}/{season_id}', name: 'program_season_show')]
    #[Entity('program', options: ['mapping'=>['program_slug'=>'slug']])]
    #[Entity('season', options: ['mapping'=>['season_id'=>'id']])]
    public function showSeason(Program $program, Season $season)
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program . ' found in program\'s table.'
            );
        }
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);

    }

    #[Route('/{program_slug}/{season_id}/{episode_slug}', name: 'program_episode_show')]
    #[Entity('program', options: ['mapping'=>['program_slug'=>'slug']])]
    #[Entity('season', options: ['mapping'=>['season_id'=>'id']])]
    #[Entity('episode', options: ['mapping'=>['episode_slug'=>'slug']])]
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program . ' found in program\'s table.'
            );
        }
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);

    }

    #[Route('/program/{slug}/edit', name: 'program_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($this->getUser() !== $program->getOwner()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can edit the program!');
        }
            if ($form->isSubmitted() && $form->isValid()) {
                $slug = $slugger->slug($program->getTitle());
                $program->setSlug($slug);
                $programRepository->save($program, true);

                return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
            }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }


}
