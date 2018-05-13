<?php

namespace App\Controller;

use App\AppConstants;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository
    )
    {
        $item = new Item();
        // add category "francais" by default for now...
        $item->addCategory($categoryRepository->find(8));

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
     * @Route("/{id}/edit", name="item_edit", options={"expose"=true})
     */
    public function edit(
        Item $item,
        Request $request,
        EntityManagerInterface $em
    ){

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        $session = $request->getSession();

        if(!$session->get('after_edit_route')){
            $referer = $request->headers->get('referer');
            $session->set('after_edit_route', $referer);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $em->persist($item);
            $em->flush();

            $this->addFlash(AppConstants::FLASH_DEFAULT, 'Item updated');

            $redirectRoute = $session->get('after_edit_route');
            $session->remove('after_edit_route');

            return new RedirectResponse($redirectRoute);

        }

        return $this->render('admin/edit.html.twig',
            [
                'form' => $form->createView(),
                'item' => $item
            ]
        );
    }


    /**
     * @Route("/", name="item_list")
     */
    public function list(ItemRepository $itemRepository)
    {
        $items = $itemRepository->findAllWithFetchJoin();

        return $this->render('admin/list.html.twig',
            [
                'items' => $items
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="item_delete")
     */
    public function delete(Item $item, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($item);
        $entityManager->flush();

        $this->addFlash(AppConstants::FLASH_DANGER, 'Item deleted');

        return $this->redirectToRoute('item_list');
    }

    /**
     * @Route("/{id}/archive", name="item_archive")
     */
    public function archive (Item $item, EntityManagerInterface $entityManager){
        $item->setDueAt(Carbon::create(3000, 1, 1, 0, 0, 0));
        $entityManager->persist($item);
        $entityManager->flush();

        $this->addFlash(AppConstants::FLASH_DEFAULT, 'Item archived');

        return $this->redirectToRoute('item_list');
    }
}
