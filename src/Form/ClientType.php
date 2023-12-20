<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de client', // Add a label for the 'name' field
            ])
            ->add('adresse', null, [
                'label' => 'Adresse de client', // Add a label for the 'adresse' field
            ])
            ->add('number', null, [
                'label' => 'Numéro de téléphone', // Add a label for the 'number' field
            ])
            ->add('mf', null, [
                'label' => 'Numéro M.F.', // Add a label for the 'mf' field
            ])
            // ->add('createdAt');
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
