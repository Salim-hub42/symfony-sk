<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['include_email'] ?? false) { // ici on vérifie si l'option 'include_email' est définie et vraie, sinon on n'ajoute pas le champ email au formulaire
            $builder->add('email', EmailType::class);
        }
        $builder
            ->add('password', PasswordType::class)
            ->add('prenom', TextType::class)
            ->add('nom', TextType::class);

        // Affiche le champ 'roles' uniquement si l'utilisateur connecté est admin
        if (isset($options['is_admin']) && $options['is_admin'] === true) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Employé' => 'ROLE_EMPLOYEE',
                ],
                'expanded' => true,
                'multiple' => true,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'include_email' => false,
            'is_admin' => false,
        ]);
    }
}
