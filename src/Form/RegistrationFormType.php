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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
        if (strpos($_SERVER['REQUEST_URI'], 'register/company') || strpos($_SERVER['REQUEST_URI'], 'company/profile/edit')) {
            $builder
                ->add('siret', TextType::class,['label' => 'Siret', 'required' => true])
                ->add('name', TextType::class,['label' => 'Votre nom', 'required' => true])
                ->add('headOffice', TextType::class,['label' => 'Siège social', 'required' => false])
                ->add('register', TextType::class,['label' => 'Registre', 'required' => false])
                ->add('mail', EmailType::class, ['label' => 'Adresse e-mail',
                    'required' => false])
                ->add('description', TextareaType::class, ['label' => 'Description',
                    'required' => false])

                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmer le mot de passe'],
                ])
                ->add('save', SubmitType::class, ['label' => "S'enregistrer"]);
        } else if (strpos($_SERVER['REQUEST_URI'], 'register/streamer') || strpos($_SERVER['REQUEST_URI'], 'streamer/profile/edit')) {
            $builder
                ->add('username', TextType::class,['label' => 'Nom d\'utilisateur', 'required' => true, 'attr' => ['placeholder' => 'Nom d’utilisateur utiliser sur la plateforme de streaming']])
                ->add('mail', EmailType::class, ['label' => 'Mail',
                    'required' => false, 'attr' => ['placeholder' => 'johndoe@email.com']])
                ->add('siret', TextType::class,['label' => 'SIRET', 'required' => false, 'attr' => ['placeholder' => '78467169500087']])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field', 'placeholder' => '**************']],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmer mot de passe'],
                ])
                ->add('isMature', CheckboxType::class, ['label' => 'Public 18+', 'required' => false])
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