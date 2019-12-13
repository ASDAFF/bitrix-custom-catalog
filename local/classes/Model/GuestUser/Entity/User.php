<?php

namespace Oip\Model\GuestUser\Entity;

class User
{
    /** @var integer $id */
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /** @return integer $id */
    public function getId(): int {
        return $this->id;
    }

}