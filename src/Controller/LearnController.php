<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/result/{item}", name="learn_handle_result")
     */
    public function handleLearnResult(
        Item $item,
        EntityManagerInterface $em,
        Request $request
    ){
        $nextReview = '';

        switch ($request->get('learn_rating')){
            case 1:
                $nextReview = '+ 15 minutes';
                break;
            case 2:
                $nextReview = '+ 90 minutes';
                break;
            case 3:
                $nextReview = '+ 6 hours';
                break;
            case 4:
                $nextReview = '+ 1 days';
                break;
            case 5:
                $nextReview = '+ 3 days';
                break;
        }

        $item->setDueAt(new \DateTime($nextReview));

        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('learn');
    }
}
