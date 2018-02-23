<?php

namespace App\Controller;

use App\AppConstants;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function create(
        Request $request,
        EntityManagerInterface $em
    )
    {
        $item = new Item();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $em->persist($item);
            $em->flush();

            $this->addFlash(AppConstants::FLASH_DEFAULT, 'Item created');

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
    public function edit(
        Item $item,
        Request $request,
        EntityManagerInterface $em
    ){

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $em->persist($item);
            $em->flush();

            $this->addFlash(AppConstants::FLASH_DEFAULT, 'Item updated');

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
        $items = $itemRepository->findBy([], ['dueAt' => 'ASC']);

        return $this->render('admin/list.html.twig',
            [
                'items' => $items
            ]
        );
    }
}
