<?php

namespace App\Controller;

use App\Form\Type\OrderType;
use App\Services\PriceCalculator\Exception\WrongTaxNumberException;
use App\Services\PriceCalculator\Order;
use App\Services\PriceCalculator\PriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(Request $request, PriceCalculator $priceCalculator): Response
    {
        $order = new Order(null, '');
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $customerPrice = $priceCalculator->calculateForOrder($order);
            } catch (WrongTaxNumberException) {
                $form->addError(new FormError('Wrong tax number'));
            }
        }

        return $this->render('shop/index.html.twig', [
            'formOrder' => $form->createView(),
            'customerPrice' => $customerPrice ?? null,
        ]);
    }
}
