<?php

namespace App\Services\PriceCalculator;

use App\Entity\Good;
use Symfony\Component\Validator\Constraints as Assert;

class Order
{
    public function __construct(
        private ?Good $good,

        #[Assert\Regex(
            pattern: '/^[A-Z]+[0-9]+$/',
            message: 'Wrong tax number. Check entered tax number and try again'
        )]
        private string $taxNumber,
    ) {
    }

    public function getGood(): ?Good
    {
        return $this->good;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setGood(?Good $good): Order
    {
        $this->good = $good;

        return $this;
    }

    public function setTaxNumber(string $taxNumber): Order
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }
}
