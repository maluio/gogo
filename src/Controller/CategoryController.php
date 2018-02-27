<?php


namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/create/{name}")
     */
    public function create(string $name, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager){
        if($categoryRepository->findOneBy(['name' => $name])){
            return new JsonResponse('category already exists', 400);
        }

        $category = new Category();
        $category->setName($name);

        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse('created');
    }

}