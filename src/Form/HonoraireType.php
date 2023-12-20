<?php

namespace App\Form;

use App\Entity\Honoraire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType; // Import the NumberType
use Symfony\Component\Validator\Constraints as Assert; // Import the Assert namespace


class HonoraireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            #->add('note')
            #->add('createdAt')
            ->add('objet')
            ->add('montantht', NumberType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'label' => 'Montant H.T',
                    'autocomplete' => 'off',
                    'type' => 'number',
                    'step' => '0.01',
                    'required' => 'required',
                    'autofocus' => 'autofocus',
                ],
                'constraints' => [
                    new Assert\NotBlank(), // Make the field required
                    new Assert\Type([      // Validate the field type
                        'type' => 'numeric', // Ensure it's a numeric value
                        'message' => 'Entrer un nombre valide.', // Custom error message
                    ]),
                ],
            ])
            #->add('tva')
            #->add('montantttc')
            #->add('rs')
            #->add('timbrefiscal')
            #->add('netapayer')
            #->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Honoraire::class,
        ]);
    }
}
