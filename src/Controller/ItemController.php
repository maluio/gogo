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
     * @Route("/create", name="item_create")
     */
    public function create(Request $request)
    {
        $item = new Item();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'Item created');

            return $this->redirectToRoute('item_list');
        }

        return $this->render('admin/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="item_edit")
     */
    public function edit(Item $item, Request $request){

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'Item updated');

            return $this->redirectToRoute('item_list');
        }

        return $this->render('admin/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/", name="item_list")
     */
    public function list(ItemRepository $itemRepository)
    {
        $items = $itemRepository->findBy([], ['dueAt' => 'DESC']);

        return $this->render('admin/list.html.twig',
            [
                'items' => $items
            ]
        );
    }
}
