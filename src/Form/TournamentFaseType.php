<?php

namespace App\Form;

use App\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\TournamentFase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TournamentFaseType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options): void {

    $notNull =  new NotBlank();
    $groups = $options['validation_groups'];

    if (array_intersect(['user', 'admin'], $groups)) {
      $builder
      ->add('name', TextType::class, ['constraints' => [$notNull]])
      ->add('tournament', EntityType::class, ['class' => Tournament::class, 'constraints' => [$notNull]]);
    }
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => TournamentFase::class,
      'allow_extra_fields' => true,
      'csrf_protection' => false,
    ]);
  }
}
