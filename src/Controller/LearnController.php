<?php

namespace App\Controller;

use App\AppConstants;
use App\Entity\Item;
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
     * @Route("/result/{item}", name="learn_handle_result")
     */
    public function handleLearnResult(
        Item $item,
        EntityManagerInterface $em,
        Request $request,
        DateTimeProvider $dateTimeProvider,
        DateTimeFormatHelper $dateTimeFormatHelper
    ){
        $nextReview = '';

        switch ($request->get('learn_rating')){
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

        $em->persist($item);
        $em->flush();

        $this->addFlash(
            AppConstants::FLASH_DEFAULT,
            'Due in ' . $dateTimeFormatHelper->formatDueDiff($newDueDate)
        );

        return $this->redirectToRoute('learn');
    }
}
