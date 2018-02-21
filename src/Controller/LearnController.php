<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LearnController extends Controller
{
    /**
     * @Route("/", name="learn")
     */
    public function index(ItemRepository $itemRepository)
    {
        $dueItem = $itemRepository->findLatestDue();
        return $this->render('learn/learn.html.twig',
            [
                'item' => $dueItem
            ]
        );
    }
}
