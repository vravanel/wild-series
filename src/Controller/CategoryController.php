<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_index')]
public function index(CategoryRepository $categoryRepository): Response
{
    $categories = $categoryRepository->findAll();
    return $this->render(
        'category/index.html.twig',
        ['categories' => $categories]
    );
}

#[Route('/category/new', name: 'category_new')]
#[IsGranted('ROLE_ADMIN')]
public function new(Request $request, CategoryRepository $categoryRepository) : Response
{
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $categoryRepository->save($category, true);   
  
        return $this->redirectToRoute('category_index');
    }

    // Render the form
    return $this->render('category/new.html.twig', [
        'form' => $form,
    ]);
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