<?php

namespace YandexTaxi\Delivery\YandexApi\Resources;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\Entities\Journal\Cursor;
use YandexTaxi\Delivery\Entities\Journal\Event;
use YandexTaxi\Delivery\YandexApi\Exceptions\InvalidCursor;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\Entities\Journal\Journal as JournalObject;
use DateTime;

/**
 * Class Journal
 *
 * @package YandexTaxi\Delivery\YandexApi\Resources
 */
class Journal extends Resource
{
    protected function getBasePath(): string
    {
        return 'cargo/integration';
    }

    /**
     * @param Cursor|null $cursor
     *
     * @return JournalObject
     * @throws InvalidCursor
     * @throws YandexApiException
     */
    public function get(?Cursor $cursor): JournalObject
    {
        $params = is_null($cursor) ? [] : ['cursor' => $cursor->getValue()];

        $result = $this->call('claims/journal', Client::API_V1, !empty($params) ? ['json' => $params] : []);

        $events = [];

        foreach ($result['events'] as $rawEvent) {
            $events[] = $this->mapEvent($rawEvent);
        }

        return new JournalObject(new Cursor($result['cursor']), $events);
    }

    private function mapEvent(array $raw): Event
    {
        return new Event(
            $raw['claim_id'],
            $raw['change_type'],
            new DateTime($raw['updated_ts']),
            empty($raw['new_status']) ? null : Status::fromCode($raw['new_status'])
        );
    }

}
