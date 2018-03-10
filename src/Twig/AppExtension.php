<?php

namespace App\Twig;

use App\Repository\ItemRepository;
use App\Repository\NoteRepository;
use App\Utils\DateTimeFormatHelper;
use App\Utils\ItemFilters;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    /**
     * @var DateTimeFormatHelper
     */
    private $dateTimeFormatHelper;

    /**
     * @var ItemFilters
     */
    private $itemFilters;
    /**
     * @var NoteRepository
     */
    private $noteRepository;

    public function __construct(
        DateTimeFormatHelper $dateTimeFormatHelper, ItemFilters $itemFilters, NoteRepository $noteRepository
    )
    {
        $this->dateTimeFormatHelper = $dateTimeFormatHelper;
        $this->itemFilters = $itemFilters;
        $this->noteRepository = $noteRepository;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('due_since', [$this, 'renderDueDiff']),
            new TwigFilter('hide_words', [$this, 'hideWords'])
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('notes_amount', [$this, 'getNumberOfNotes'])
        ];
    }

    public function renderDueDiff(\DateTime $dueDate): string
    {
        return $this->dateTimeFormatHelper->formatDueDiff($dueDate);
    }

    public function getNumberOfNotes(): int
    {
        return $this->noteRepository->getNotesCount();
    }

    public function hideWords(string $text, $maskCharacter=null, $tagClass='', $tagname='strong'): string
    {
        return $this->itemFilters->replaceMarker($text, $maskCharacter, $tagname, $tagClass);
    }
}
