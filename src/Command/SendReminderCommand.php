<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Utils\ItemFilters;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendReminderCommand extends ContainerAwareCommand
{

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var ItemFilters
     */
    private $itemFilters;

    /**
     * @var MarkdownParserInterface
     */
    private $markdownParser;

    public function __construct(
        ItemRepository $itemRepository,
        ItemFilters $itemFilters,
        MarkdownParserInterface $markdownParser
    ) {
        parent::__construct();
        $this->itemRepository = $itemRepository;
        $this->itemFilters = $itemFilters;
        $this->markdownParser = $markdownParser;
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

        $content = $this->createContent($dueItems);

        $response = $this->sendMail(
            count($dueItems),
            $content
        );
        $output->writeln('Sendgrid response code: ' . $response->statusCode());
        $output->writeln('Sendgrid response body: ' . $response->body());
    }

    protected function sendMail(int $numberOfDueItems, string $content)
    {
        $apiKey = getenv('SENDGRID_API_KEY');

        $from = new \SendGrid\Email('Gogo', "do-not-reply@example.com");
        $subject = 'Gogo due cards: ' . $numberOfDueItems;

        $to = new \SendGrid\Email('Malu', getenv('MAIL_RECIPIENT'));

        $content = new \SendGrid\Content("text/html", $content);
        $mail = new \SendGrid\Mail($from, $subject, $to, $content);
        $sg = new \SendGrid($apiKey);

        return $sg->client->mail()->send()->post($mail);
    }

    /**
     * @var $items Item[]
     * @return string
     */
    private function createContent(array $items): string
    {

        $content = '';

        foreach ($items as $item) {
            $renderedItem = $this->itemFilters->replaceMarker($item->getQuestion(), '*');

            $stringBeforeLineBreak = strstr($renderedItem, PHP_EOL, true);
            $renderedItem = $stringBeforeLineBreak ? $stringBeforeLineBreak : $renderedItem;

            $renderedItem = substr($renderedItem, 0, 75) . ' ...';

            if ($cats = $item->getCategories()->getValues()) {
                $renderedItem = $this->createCategoryContent($cats) . $renderedItem;
            }

            $renderedItem = '* ' . $renderedItem;

            $renderedItem = $this->markdownParser->transformMarkdown($renderedItem);

            /** @var $item Item */
            $content .= $renderedItem;
        }

        return $content;
    }

    /**
     * @param Category[] $categories
     * @return string
     */
    private function createCategoryContent(array $categories): string
    {
        $parsed = array_map(
            function ($cat) {
                return $cat->getName();
            },
            $categories
        );
        $parsed = implode($parsed, ', ');
        return '[' . $parsed . '] ';
    }
}
