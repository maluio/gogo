<?php


namespace App\Controller;

use App\AppConstants;
use App\Entity\Item;
use App\Entity\Rating;
use App\Learn\LearnHandlerInterface;
use App\Repository\ItemRepository;
use App\Utils\DateTimeFormatHelper;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    /**
     * @Route("/react/learn/rate/{item}", options={"expose"=true}, name="learn_rate")
/*     */
    public function rate(
        Item $item,
        EntityManagerInterface $em,
        Request $request,
        DateTimeFormatHelper $dateTimeFormatHelper,
        LearnHandlerInterface $learnHandler
    ){
        $content = $request->getContent();
        $content = json_decode($content, true);

        $rating = $content['learn_rating'];

        $newDueDate = $learnHandler->handle($item, $rating)->getNewDueDate();

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

    /**
     * @Route("/react/get-due-item", options={"expose"=true}, name="learn_react_due")
     */
    public function reactDueItem(ItemRepository $itemRepository, Request $request)
    {
        $dueItem = $itemRepository->findLatestDue();

        $serializer = $this->get('jms_serializer');

        $dueItem = ['item' => $dueItem];

        $json = $serializer->serialize($dueItem, 'json');
       // dump($json);

        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route("/react/", name="learn_react")
     */
    public function react(Request $request)
    {

        return $this->render('learn/react.html.twig',
            [
                'basicAuthValue' => $request->headers->get('Authorization')
            ]
        );
    }

}