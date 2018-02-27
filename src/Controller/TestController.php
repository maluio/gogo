<?php


namespace App\Controller;

use App\Repository\ItemRepository;
use JMS\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{

    /**
     * @Route("/react/", name="learn_react")
     */
    public function react(ItemRepository $itemRepository)
    {
        $dueItem = $itemRepository->findLatestDue();

       // $serializer = $this->get('jms_serializer');

        return $this->render('learn/react.html.twig',
            [
                'item' => $dueItem
            ]
        );
    }
}