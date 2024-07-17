<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use App\Enum\Gender;
use App\Entity\Player;
use Symfony\Component\Validator\Constraints\Range;

class PlayerType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $groups = $options['validation_groups'];

        $notNull =  new NotBlank();
        $gender = new Choice([
            'choices' => Gender::cases(),
            'message' => 'Choose a valid gender.',
        ]);
        $range = new Range(['min' => 1, 'max' => 100]);

        if (array_intersect(['user', 'admin'], $groups)) {
            $builder->add('name', TextType::class, ['constraints' => [$notNull] ])
            ->add('lastname', TextType::class, ['constraints' => [$notNull] ])
            ->add('power', IntegerType::class, ['constraints' => [$notNull, $range]])
            ->add('speed', IntegerType::class, ['constraints' => [$notNull, $range]])
            ->add('reaction', IntegerType::class, ['constraints' => [$notNull, $range]])
            ->add('gender', EnumType::class, ['class' => Gender::class, 'constraints' => [$gender] ]);
        }

        if (array_intersect(['admin'], $groups)) {}
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Player::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
