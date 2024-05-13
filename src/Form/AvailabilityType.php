<?php

namespace App\Form;

use App\Entity\Availability;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

class AvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'invalid_message' => 'Veuillez saisir une date valide.',
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'invalid_message' => 'Veuillez saisir une date valide.',
            ])
            ->add('price_per_day', NumberType::class, [
                'label' => 'Prix par jour',
                'invalid_message' => 'Veuillez renseigner un nombre valide.',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Disponible' => true,
                    'Non Disponible' => false,
                ],
                'expanded' => true,  
                'label_attr' => [
                    'class' => 'form-check-label',  
                ],
            ])
            ->add('vehicle', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => function ($vehicle) {
                    return $vehicle->getBrand() . ' ' . $vehicle->getModel();
                },
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...))
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                
                if (!isset($data['start_date']) || !isset($data['end_date'])) {
                    return;
                } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['start_date']) ||
                !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['end_date'])) {
                    return;
                }       
                $startDate = \DateTime::createFromFormat('Y-m-d', $data['start_date']);
                $endDate = \DateTime::createFromFormat('Y-m-d', $data['end_date']);

                $availability = $event->getForm()->getData();
                $availability->setStartDateValue($startDate);
                $availability->setEndDateValue($endDate);
                    
                });
    }

     public function attachTimestamps(PostSubmitEvent $event) : void
    {
        $data = $event->getData();
        if (!($data instanceof Availability)) {
            return;
        } 
        
        $data->setUpdatedAt(new \DateTimeImmutable());
        if (!$data->getId()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
        
    }  

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Availability::class,
        ]);
    }
}
