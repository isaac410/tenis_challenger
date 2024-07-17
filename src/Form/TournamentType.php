<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Enum\Gender;
use App\Entity\Tournament;
use App\Enum\StatusTournament;
use Symfony\Component\Validator\Constraints\Choice;

class TournamentType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $notNull =  new NotBlank();
        $groups = $options['validation_groups'];

        $gender = new Choice([
            'choices' => Gender::cases(),
            'message' => 'Choose a valid gender.',
        ]);

        $status = new Choice([
            'choices' => StatusTournament::cases(),
            'message' => 'Choose a valid status.',
        ]);

        if (array_intersect(['user', 'admin'], $groups)) {
            $builder
            ->add('name', TextType::class, ['constraints' => $notNull])
            ->add('gender', EnumType::class, ['class' => Gender::class, 'constraints' => [$gender]])
            ->add('status', EnumType::class, [
                'class' => StatusTournament::class,
                'empty_data' => StatusTournament::pendding->value,
                'constraints' => [$status]
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
