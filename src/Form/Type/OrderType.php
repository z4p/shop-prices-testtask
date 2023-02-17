<?php

namespace App\Form\Type;

use App\Entity\Good;
use App\Repository\GoodRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{
    public function __construct(private readonly GoodRepository $goodRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $goods = $this->goodRepository->findAll();

        $builder
            ->add('good', ChoiceType::class, [
                'label' => 'Choose Item to buy',
                'choices' => $goods,
                'choice_label' => fn (Good $good) => $good->getName(),
            ])
            ->add('taxNumber', TextType::class, ['label' => 'Your tax number'])
            ->add('order', SubmitType::class, ['label' => 'Calculate Price']);
    }
}
