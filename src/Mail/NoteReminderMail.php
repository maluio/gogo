<?php


namespace App\Mail;


use App\Entity\Note;

/**
 * Class NoteReminderMail
 * @package App\Mail
 */
class NoteReminderMail
{


    public function __construct(\Twig_Environment $templating)
    {

        $this->templating = $templating;
    }

    /**
     * @param $notes Note[]
     * @return string
     * @throws
     */
    public function createContent($notes): string
    {
        return $this->templating->render('mail/note-reminder.html.twig', ['notes' => $notes]);
    }
}