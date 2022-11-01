<?php

namespace YandexTaxi\Delivery\Entities\Journal;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class Journal
 *
 * @package YandexTaxi\Delivery\Dto\Journal
 */
class Journal
{
    /** @var Cursor */
    private $cursor;

    /** @var Event[] */
    private $events;

    /**
     * Journal constructor.
     *
     * @param Cursor  $cursor
     * @param Event[] $events
     */
    public function __construct(Cursor $cursor, array $events = [])
    {
        $this->cursor = $cursor;
        $this->events = $events;
    }

    public function getCursor(): Cursor
    {
        return $this->cursor;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}
