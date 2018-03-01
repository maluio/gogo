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

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/items/{item}/rate", options={"expose"=true}, name="api_rate_item")
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

       return new JsonResponse('ok');
    }

    /**
     * @Route("/items", options={"expose"=true}, name="api_items")
     */
    public function getItems(ItemRepository $itemRepository, Request $request)
    {
        if($request->get('due') && 'true' === $request->get('due')){
            $items = $itemRepository->findAllDue();
        }
        else {
            $items = $itemRepository->findAll();
        }

/*        foreach ($items as $item){
            $html['categories'] = $this->renderView('util/_categories.html.twig', [
                'categories' => $item->getCategories()
            ]);
            $item->html = $html;
        }*/


        $serializer = $this->get('jms_serializer');

        $json = $serializer->serialize($items, 'json');

        return new JsonResponse($json, 200, [], true);

    }

}