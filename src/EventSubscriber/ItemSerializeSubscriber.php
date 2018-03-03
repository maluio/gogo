<?php


namespace App\EventSubscriber;


use App\Entity\Item;
use App\Utils\ItemFilters;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Twig\Environment;

class ItemSerializeSubscriber implements EventSubscriberInterface
{

    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var ItemFilters
     */
    private $itemFilters;
    /**
     * @var MarkdownParserInterface
     */
    private $markdownParser;

    public function __construct(Environment $twig, ItemFilters $itemFilters, MarkdownParserInterface $markdownParser)
    {
        $this->twig = $twig;
        $this->itemFilters = $itemFilters;
        $this->markdownParser = $markdownParser;
    }

    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => Item::class, // if no class, subscribe to every serialization
                'format' => 'json', // optional format
                'priority' => 0, // optional priority
            )
        );
    }

    public function onPostSerialize(ObjectEvent $event){

        if(false === $event->getObject() instanceof Item){
            return;
        }

        /* @var $item \App\Entity\Item */
        $item = $event->getObject();
        $html['categories'] = $this->twig->render('util/_categories.html.twig', [
            'categories' => $item->getCategories()
        ]);

        $html['rating_indicator'] = $this->twig->render('util/_rating_indicator.html.twig', [
            'ratings' => $item->getRatings()
        ]);

        $html['question']= $this->markdownParser->transformMarkdown(
            $this->itemFilters->replaceMarkerWithHtmlTag($item->getQuestion(), null, 'strong')
        );
        $html['question_masked']= $this->markdownParser->transformMarkdown(
            $this->itemFilters->replaceMarkerWithHtmlTag($item->getQuestion(), '*')
        );
        $item->getAnswer() ? $html['answer'] = $this->markdownParser->transformMarkdown($item->getAnswer()) : null;

        $event->getVisitor()->addData('html',$html);

    }


}