<?php

namespace Oip\SocialStore\Order\Repository;

use Oip\SocialStore\Order\Entity;

interface RepositoryInterface
{

    /**
     * @param int $orderId
     * @return Entity\Order
     */
    public function getById(int $orderId): Entity\Order;

    /**
     * @param int $userId
     * @return Entity\OrderCollection
     */
    public function getAllByUserId(int $userId): Entity\OrderCollection;

    /**
     * @param Entity\Order $order
     * @return void
     * */
    public function addOrder(Entity\Order $order): void;

    /**
     * @param int $orderId
     * @return void
    */
    public function removeOrder(int $orderId): void;
}