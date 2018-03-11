<?php

namespace App\Command;

use App\Entity\Item;
use App\Mail\DueItemReminderMail;
use App\Mail\MailProviderInterface;
use App\Mail\NoteReminderMail;
use App\Repository\ItemRepository;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNoteReminderCommand extends ContainerAwareCommand
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var NoteRepository
     */
    private $noteRepository;
    /**
     * @var NoteReminderMail
     */
    private $noteReminderMail;

    public function __construct(
        \Swift_Mailer $mailer,
        NoteRepository $noteRepository,
        NoteReminderMail $noteReminderMail
    ) {
        parent::__construct();
        $this->mailer = $mailer;
        $this->noteRepository = $noteRepository;
        $this->noteReminderMail = $noteReminderMail;
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('gogo:send-note-reminder')
            ->setDescription('Sends reminder for notes')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Sends reminder for notes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notes = $this->noteRepository->findAll();

        $output->writeln('# of due notes: ' . count($notes));

        if (0 === count($notes)) {
            return;
        }

        $message = new \Swift_Message();
        $message->setSubject(count($notes) . ' notes are waiting')
            ->setContentType('text/html')
            ->setBody(
                $this->noteReminderMail->createContent($notes)
            )
            ->setFrom("do-not-reply@example.com")
            ->setTo(getenv('MAIL_RECIPIENT'));

        $numberOfSuccessfulRecipients = $this->mailer->send($message);
        $output->writeln('Number of successful recipients: ' . $numberOfSuccessfulRecipients);
    }
}
