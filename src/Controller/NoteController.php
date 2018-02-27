<?php

namespace App\Controller;

use App\AppConstants;
use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NoteController extends Controller
{
    /**
     * @Route("/note", name="note_index")
     */
    public function index(NoteRepository $noteRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $notes = $noteRepository->findAll();

        $form = $this->createForm(NoteType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $note = $form->getData();
            $entityManager->persist($note);
            $entityManager->flush();

            $this->addFlash(AppConstants::FLASH_DEFAULT, 'Note created');

            return $this->redirectToRoute('note_index');
        }

        return $this->render('note/list.html.twig', [
            'notes' => $notes,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/note/delete/{id}", name="note_delete")
     */
    public function delete(Note $note, EntityManagerInterface $entityManager){
        $entityManager->remove($note);
        $entityManager->flush();

        $this->addFlash(AppConstants::FLASH_DANGER, 'Note deleted!');

        return $this->redirectToRoute('note_index');
    }
}
