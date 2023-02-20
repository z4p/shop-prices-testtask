<?php

namespace App\Services\PriceCalculator;

use App\Repository\CountryRepository;
use App\Services\PriceCalculator\Exception\WrongTaxNumberException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class PriceCalculator
{
    public function __construct(
        private readonly CountryRepository $countryRepository
    ) {
    }

    /**
     * @throws WrongTaxNumberException
     */
    public function calculateForOrder(Order $order): float
    {
        try {
            $country = $this->countryRepository->detectByTaxIdentifier($order->getTaxNumber());
        } catch (NonUniqueResultException|NoResultException) {
            throw new WrongTaxNumberException();
        }

        return $order->getGood()->getPrice() * (1 + $country->getVat() / 100);
    }
}
