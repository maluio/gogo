<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin")
 */
class ItemController extends Controller
{

    /**
     * @Route("/", name="item_list")
     */
    public function index(ItemRepository $itemRepository, Request $request)
    {
        $item = new Item();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $item = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('item_list');
        }

        $items = $itemRepository->findAll();

        return $this->render('admin/list.html.twig',
            [
                'items' => $items,
                'form' => $form->createView()
            ]
        );
    }
}
