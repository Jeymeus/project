<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints as Assert;


class SearchAvailabilityType extends AbstractType
{
    const MAX_PRICE = [
        500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir une date de début.'
                    ]),
                ],
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir une date de fin.'
                    ])
                ],
            ])
            ->add('maxPrice', ChoiceType::class, [
                'label' => 'Prix maximum de la location',
                'choices' => array_combine(self::MAX_PRICE, self::MAX_PRICE),
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez choisir un prix maximum.'
                    ])
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if ($data['start_date'] > $data['end_date']) {
                    $form->get('start_date')->addError(new FormError('La date de début doit être inférieure à la date de fin.'));
                    return;
                }
            });
    }

   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
