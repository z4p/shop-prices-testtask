<?php

namespace App\Tests\Services\PriceCalculator;

use App\Entity\Country;
use App\Entity\Good;
use App\Repository\CountryRepository;
use App\Services\PriceCalculator\Exception\WrongTaxNumberException;
use App\Services\PriceCalculator\Order;
use App\Services\PriceCalculator\PriceCalculator;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    /**
     * @dataProvider prices
     */
    public function testCalculateForOrder(Order $order, ?Country $country, float $priceWithVAT): void
    {
        $repo = $this->createMock(CountryRepository::class);
        $repo->expects(self::once())
            ->method('detectByTaxIdentifier')
            ->willReturnCallback(function () use ($country) {
                if ($country) {
                    return $country;
                } else {
                    throw new WrongTaxNumberException();
                }
            });

        $calculator = new PriceCalculator($repo);
        if (!$country) {
            $this->expectException(WrongTaxNumberException::class);
            $calculator->calculateForOrder($order);
        } else {
            $price = $calculator->calculateForOrder($order);
            $this->assertEquals($priceWithVAT, $price);
        }
    }

    public function prices(): array
    {
        $country = (new Country())->setVat('20');
        $good = (new Good())
            ->setName('Macbook Air 2007')
            ->setPrice(1000);
        $order = new Order($good, 'GE123');

        return [
            'Correct tax number' => [
                'order' => $order,
                'country' => $country,
                'priceWithVAT' => 1200,
            ],
            'Incorrect tax number' => [
                'order' => $order,
                'country' => null,
                'priceWithVAT' => 0,
            ],
        ];
    }
}
