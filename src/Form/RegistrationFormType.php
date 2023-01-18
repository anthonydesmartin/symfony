<?php

namespace App\Form;

use App\Entity\Streamer;
use App\Entity\Company;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (strpos($_SERVER['REQUEST_URI'], 'register/company')) {
            $builder
                ->add('siret', TextType::class,['label' => 'Siret', 'required' => true])
                ->add('name', TextType::class,['label' => 'Votre nom', 'required' => true])
                ->add('headOffice', TextType::class,['label' => 'Dirigeant', 'required' => true])
                ->add('register', TextType::class,['label' => 'Register', 'required' => true])
                ->add('mail', EmailType::class, ['label' => 'Mail',
                    'required' => true])

                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Comfirmer le Password'],
                ])
                ->add('save', SubmitType::class, ['label' => "S'enregistrer"]);
        } else if (strpos($_SERVER['REQUEST_URI'], 'register/streamer')) {
            $builder
                ->add('username', TextType::class,['label' => 'Pseudo', 'required' => true])
                ->add('mail', EmailType::class, ['label' => 'Mail',
                    'required' => true])
                ->add('siret', TextType::class,['label' => 'SIRET'])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Comfirmer le Password'],
                ])
                ->add('Followers', TextType::class, ['label' => "Nombre d'abonnés"])
                ->add('isMature', CheckboxType::class, ['label' => 'Public 18+'])
                ->add('idStreamer', TextType::class,['label' => 'ID twitch'])
                ->add('save', SubmitType::class, ['label' => "S'enregistrer"]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        if (strpos($_SERVER['REQUEST_URI'], 'register/streamer')) {
            $resolver->setDefaults([
                'data_class' => Streamer::class,
            ]);
        } else if (strpos($_SERVER['REQUEST_URI'], 'register/company')) {
            $resolver->setDefaults([
                'data_class' => Company::class,
            ]);
        }
    }
}
