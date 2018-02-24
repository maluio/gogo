<?php

namespace App\Controller;

use App\AppConstants;
use App\Entity\Item;
use App\Entity\Rating;
use App\Repository\ItemRepository;
use App\Utils\DateTimeFormatHelper;
use App\Utils\DateTimeProvider;
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
     * @Route("/learn/rate/{item}", name="learn_rate")
     */
    public function rate(
        Item $item,
        EntityManagerInterface $em,
        Request $request,
        DateTimeProvider $dateTimeProvider,
        DateTimeFormatHelper $dateTimeFormatHelper
    ){
        $nextReview = '';

        $rating = $request->get('learn_rating');

        switch ($rating){
            case 1:
                $nextReview = '';
                break;
            case 2:
                $nextReview = '+ 90 minutes';
                break;
            case 3:
                $nextReview = '+ 6 hours';
                break;
            case 4:
                $nextReview = '+ 1 day';
                break;
            case 5:
                $nextReview = '+ 3 days';
                break;
        }

        $newDueDate = $dateTimeProvider->fromString($nextReview);

        $item->setDueAt($newDueDate);
        $item->addRating(new Rating($rating));

        $em->persist($item);
        $em->flush();

        $this->addFlash(
            AppConstants::FLASH_DEFAULT,
            'Due in ' . $dateTimeFormatHelper->formatDueDiff($newDueDate)
        );

        return $this->redirectToRoute('learn');
    }
}
