<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'required' => true,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'required' => true,
            ])
            ->add('format', ChoiceType::class, [
                'label' => 'Format',
                'choices' => [
                    'Bannières de publicité' => 'banner',
                    'Placement de produit' => 'product_show',
                    'Test de produit' => 'product_test',
                    'Post sur les réseaux sociaux' => 'post',
                    'Vidéo' => 'video',
                    'Photo' => 'photo',
                ],
                'required' => true,
            ])
            ->add('modalities', TextareaType::class, [
                'label' => 'Modalités',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer la demande',
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
