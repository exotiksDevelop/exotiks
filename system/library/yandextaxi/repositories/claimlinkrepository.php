<?php

namespace YandexTaxi\Repositories;

use YandexTaxi\Delivery\ClaimLink\ClaimLink;
use YandexTaxi\Delivery\ClaimLink\ClaimLinkRepository as ClaimLinkRepositoryInterface;

/**
 * Class ClaimLinkRepository
 *
 * @package YandexTaxi\Repositories
 */
class ClaimLinkRepository extends BaseRepository implements ClaimLinkRepositoryInterface {
    private const TABLE_NAME = 'yandextaxi_claim_links';

    public function get(string $id): ?ClaimLink {
        $item = $this->findByPk($id);

        if (is_null($item)) {
            return null;
        }

        return new ClaimLink(
            $item['id'],
            $item['meta_hash'],
            $item['address'],
            $item['lat'],
            $item['lon'],
            $item['claim_id'],
            $item['version']
        );
    }

    public function delete(string $id): void {
        $this->db->query("DELETE FROM `{$this->getTableName()}` WHERE `id` = '{$this->db->escape($id)}'");
    }

    public function store(ClaimLink $link): void {
        $stored_link = $this->get($link->getId());

        if (is_null($stored_link)) {
            $this->insert([
                'id' => $link->getId(),
                'meta_hash' => $link->getMetaHash(),
                'address' => $link->getAddress(),
                'lat' => $link->getLat(),
                'lon' => $link->getLon(),
                'claim_id' => $link->getClaimId(),
                'version' => $link->getVersion(),
            ]);
            return;
        }

        $this->update(
            [
                'meta_hash' => $link->getMetaHash(),
                'address' => $link->getAddress(),
                'lat' => $link->getLat(),
                'lon' => $link->getLon(),
                'claim_id' => $link->getClaimId(),
                'version' => $link->getVersion(),
            ],
            ['id' => $link->getId()]
        );
    }

    protected function getTableName(): string {
        return DB_PREFIX . self::TABLE_NAME;
    }
}
