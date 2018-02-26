<?php

namespace App\Controller;

use App\AppConstants;
use App\Entity\Item;
use App\Entity\Rating;
use App\Exception\InitializationException;
use App\Learn\LearnHandler;
use App\Repository\ItemRepository;
use App\Utils\DateTimeFormatHelper;
use App\Utils\DateTimeProvider;
use App\Utils\LoggerTrait;
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
        DateTimeFormatHelper $dateTimeFormatHelper,
        LearnHandler $learnHandler
    ){
        $rating = $request->get('learn_rating');

        try {
            $newDueDate = $learnHandler->handle($item, $rating)->getNewDueDate();
        }
        catch (InitializationException $exception){
            $this->addFlash(
                AppConstants::FLASH_DANGER,
                'Issue when trying to handle learn rating'
            );
            return $this->redirectToRoute('learn');
        }

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
