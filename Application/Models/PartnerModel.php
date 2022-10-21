<?php

namespace Application\Models;

class PartnerModel
{
    private int $partner_name;

    /**
     * @return int
     */
    public function getPartnerName(): int
    {
        return $this->partner_name;
    }

    /**
     * @param int $partner_name
     */
    public function setPartnerName(int $partner_name): void
    {
        $this->partner_name = $partner_name;
    }
}