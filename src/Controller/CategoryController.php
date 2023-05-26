<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;

class CategoryController extends AbstractController
{
    #[Route('/category/', name: 'category_index')]
public function index(CategoryRepository $categoryRepository): Response
{
    $categories = $categoryRepository->findAll();
    return $this->render(
        'category/index.html.twig',
        ['categories' => $categories]
    );
}

#[Route('/category/{categoryName}', name: 'category_show')]
public function show(string $categoryName, categoryRepository $categoryRepository, ProgramRepository $programRepository): Response
{
    $category = $categoryRepository->findOneBy(['name' => $categoryName]);
    $programs = $programRepository->findBy(
        ['category' => $category], ['id' => 'DESC'], 3
    );

    if (!$category) {
        throw $this->createNotFoundException(
            'No category : '.$categoryName .' found in category table.'
        );
    }

    return $this->render('category/show.html.twig', [
        'category' => $category,
        'programs' => $programs,
    ]);
}

}