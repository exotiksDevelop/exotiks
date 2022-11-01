<?php

namespace YandexTaxi\Delivery\Services;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\Journal\Event;
use YandexTaxi\Delivery\Entities\Journal\Journal;
use YandexTaxi\Delivery\YandexApi\Exceptions\InvalidCursor;
use YandexTaxi\Delivery\YandexApi\Repositories\CursorRepository;
use YandexTaxi\Delivery\YandexApi\Resources\Journal as JournalService;

/**
 * Class EventService
 *
 * @package YandexTaxi\Delivery\Services
 */
class EventService
{
    /** @var JournalService */
    private $journal;

    /** @var CursorRepository */
    private $cursorRepository;

    public function __construct(JournalService $journal, CursorRepository $cursorRepository)
    {
        $this->journal = $journal;
        $this->cursorRepository = $cursorRepository;
    }

    /**
     * @return Event[]
     */
    public function findNew(): array
    {
        $journal = $this->findJournal();

        $this->cursorRepository->store($journal->getCursor());
        $this->cursorRepository->deleteOlderThanYesterday();

        return $journal->getEvents();
    }

    private function findJournal(): Journal
    {
        $cursor = $this->cursorRepository->getLatest();

        try {
            return $this->journal->get($cursor);
        } catch (InvalidCursor $exception) {
            $this->cursorRepository->delete($cursor);
            $cursor = $this->cursorRepository->getLatest();

            return $this->journal->get($cursor);
        }
    }
}
