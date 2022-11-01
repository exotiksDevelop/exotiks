<?php

namespace YandexTaxi\Repositories;

use DateTime;
use YandexTaxi\Delivery\Entities\Journal\Cursor;
use YandexTaxi\Delivery\YandexApi\Repositories\CursorRepository as CursorRepositoryInterface;

/**
 * Class CursorRepository
 *
 * @package YandexTaxi\Repositories
 */
class CursorRepository extends BaseRepository implements CursorRepositoryInterface {
    private const TABLE_NAME = 'yandextaxi_journal_cursors';

    public function getLatest(): ?Cursor {
        $item = $this->findOne(null, ['datetime' => 'DESC']);

        if (is_null($item)) {
            return null;
        }

        return new Cursor($item['value']);
    }

    public function deleteOlderThanYesterday(): void {
        $dateTime = new DateTime();
        $dateTime->modify('-1 day');
        $dateTime->setTime(23, 59, 59);
        $this->db->query("DELETE FROM `{$this->getTableName()}`"
            . " WHERE `datetime` <= '{$dateTime->format('Y-m-d H:i:s')}'");
    }

    public function store(Cursor $cursor): void {
        $this->insert([
            'value' => $cursor->getValue(),
            'datetime' => (new DateTime())->format('Y-m-d H:i:s'),
        ]);
    }

    public function delete(Cursor $cursor): void
    {
        $this->db->query("DELETE FROM `{$this->getTableName()}` WHERE `value` = '{$this->db->escape($cursor->getValue())}'");
    }

    protected function getTableName(): string {
        return DB_PREFIX . self::TABLE_NAME;
    }
}
