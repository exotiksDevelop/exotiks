<?php

namespace YandexTaxi\Delivery\ClaimLink;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class ClaimLinkRepository
 *
 * @package YandexTaxi\Delivery\ClaimLink
 */
interface ClaimLinkRepository
{
    public function get(string $id): ?ClaimLink;

    public function store(ClaimLink $link): void;

    public function delete(string $id): void;
}
