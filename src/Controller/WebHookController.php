<?php


namespace App\Controller;


use App\Message\DueItemReminderMessage;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webhooks", name="webhooks_")
 */
class WebHookController extends Controller
{

    /**
     * @Route("/send-due-item-reminder", name="send_due_item_reminder")
     */
    public function sendDueItemReminder(
        ItemRepository $itemRepository,
        DueItemReminderMessage $dueItemReminderMessage,
        EntityManagerInterface $entityManager
    ){
        $dueItems = $itemRepository->findAllDueWithoutReminderSent();

        if (0 === count($dueItems)) {
            return new JsonResponse('No due items');
        }

        foreach ($dueItems as $item){
            $body = $dueItemReminderMessage->renderItem($item);

            $client = $this->get('eight_points_guzzle.client.messenger_send_message');
            $client->request('POST', '', ['json' => ['text' => $body]]);

            $item->setIsReminderSend(true);
            $entityManager->persist($item);
        }

        $entityManager->flush();

        return new JsonResponse('Reminder sent');
    }
}