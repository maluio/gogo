<?php


namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rating;
use App\Learn\LearnHandlerInterface;
use App\Repository\ItemRepository;
use App\Utils\DateTimeFormatHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/items/{item}/rate", options={"expose"=true}, name="api_rate_item", methods={"POST"})
     */
    public function rate(
        Item $item,
        EntityManagerInterface $em,
        Request $request,
        DateTimeFormatHelper $dateTimeFormatHelper,
        LearnHandlerInterface $learnHandler
    ) {
        $content = $request->getContent();
        $content = json_decode($content, true);

        $rating = $content['learn_rating'];

        $newDueDate = $learnHandler->handle($item, $rating)->getNewDueDate();

        $item->setDueAt($newDueDate);
        $item->addRating(new Rating($rating));
        $item->setIsReminderSend(false);

        $em->persist($item);
        $em->flush();

        $response = 'Item due ' . $dateTimeFormatHelper->formatDueDiff($newDueDate);

        return new JsonResponse($response);
    }

    /**
     * @Route("/items", options={"expose"=true}, name="api_get_items", methods={"GET"})
     */
    public function getItems(ItemRepository $itemRepository, Request $request)
    {
        if ($request->get('due') && 'true' === $request->get('due')) {
            $items = $itemRepository->findAllDue();
        } else {
            $items = $itemRepository->findAll();
        }

        $serializer = $this->get('jms_serializer');

        $json = $serializer->serialize($items, 'json');

        // This stunt became necessary due to the serializer escaping all quotation marks with slash in the "data" property
        // which breaks the resulting json. Thanks a lot Mysql for not supporting UTF8 properly in your awesome Json datatype,
        // forcing me to store the data property as string
        $array = json_decode($json, true);
        $array = array_map(function($item){
            $item['data'] = json_decode($item['data']);
            return $item;
        }, $array);
        $json = json_encode($array);



        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route("/migrate", options={"expose"=true}, name="api_migrate", methods={"GET"})
     */
    public function migrate(){
        /** @var EntityManagerInterface $emNew */
        $emNew = $this->get('doctrine.orm.new_entity_manager');

        $items = $this->getDoctrine()->getRepository(Item::class, 'default')->findAll();
        /* @var $item Item */
        foreach ($items as $item){
            $newItem = clone $item;
            $emNew->persist($newItem);
        }
        $emNew->flush();

        return new JsonResponse('ok');
    }

}