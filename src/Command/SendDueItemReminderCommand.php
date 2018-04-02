<?php

namespace App\Command;

use App\Entity\Item;
use App\Mail\DueItemReminderMail;
use App\Mail\MailProviderInterface;
use App\Repository\ItemRepository;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendDueItemReminderCommand extends ContainerAwareCommand
{

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var DueItemReminderMail
     */
    private $dueItemReminderMail;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(
        ItemRepository $itemRepository,
        DueItemReminderMail $dueItemReminderMail,
        \Swift_Mailer $mailer
    ) {
        parent::__construct();
        $this->itemRepository = $itemRepository;
        $this->dueItemReminderMail = $dueItemReminderMail;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('gogo:send-reminder')
            ->setDescription('Sends reminder for due items')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Sends reminder for due items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dueItems = $this->itemRepository->findAllDue();

        $output->writeln('# of due items: ' . count($dueItems));

        if (0 === count($dueItems)) {
            return;
        }

        $connection = new AMQPStreamConnection('messagebroker_broker_1', 5672, 'malu', 'bhtdrk');
        $channel = $connection->channel();

        $channel->queue_declare('tasks', false, true, false, false);


        $msg = new AMQPMessage('hi there');

        $channel->basic_publish($msg);

        $channel->close();
        $connection->close();


/*        $message = new \Swift_Message();
        $message->setSubject(count($dueItems) . ' due items are waiting')
            ->setContentType('text/html')
            ->setBody(
                $this->dueItemReminderMail->createContent($dueItems)
            )
            ->setFrom("do-not-reply@example.com")
            ->setTo(getenv('MAIL_RECIPIENT'));

        $numberOfSuccessfulRecipients = $this->mailer->send($message);*/


        //$output->writeln('Number of successful recipients: ' . $numberOfSuccessfulRecipients);
    }
}
