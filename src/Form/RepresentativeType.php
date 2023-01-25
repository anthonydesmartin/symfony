<?php

namespace App\Form;

use App\Entity\Representative;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepresentativeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name' , TextType::class, ['label' => 'Prénom', 'required' => true])
            ->add('last_name', TextType::class, ['label' => 'Nom', 'required' => true])
            ->add('save', SubmitType::class, ['label' => "Signer"]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Representative::class,
        ]);
    }
}
