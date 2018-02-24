<?php

namespace App\Command;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendReminderCommand extends ContainerAwareCommand
{

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        parent::__construct();
        $this->itemRepository = $itemRepository;
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('malunki:send-reminder')
            ->setDescription('Sends reminder for due items')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Sends reminder for due items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dueItems = $this->itemRepository->findAllDue();

        $output->writeln('# of due items: ' . count($dueItems));

        if (count($dueItems) > 0) {
            $content = $this->createContent($dueItems);

            $response = $this->sendMail(
                count($dueItems),
                $content
            );
            $output->writeln('Sendgrid response code: ' . $response->statusCode());
            $output->writeln('Sendgrid response body: ' . $response->body());
        }
    }

    protected function sendMail(int $numberOfDueItems, string $content)  {
        $apiKey = getenv('SENDGRID_API_KEY');

        $from = new \SendGrid\Email('Gogo', "do-not-reply@example.com");
        $subject = 'Malunki due cards: ' . $numberOfDueItems;

        $to = new \SendGrid\Email('Malu', getenv('MAIL_RECIPIENT'));

        $content = new \SendGrid\Content("text/plain", $content);
        $mail = new \SendGrid\Mail($from, $subject, $to, $content);
        $sg = new \SendGrid($apiKey);

        return $sg->client->mail()->send()->post($mail);
    }

    /**
     * @var Item[]
     * @return string
     */
    private function createContent(array $items): string {

        $content = '';

        foreach ($items as $item){
            /** @var $item Item */
            $content .= '** ' . $item->getQuestion() . "\r\n\r\n";
        }

        return $content;
    }
}
