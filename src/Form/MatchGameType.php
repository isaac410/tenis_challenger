<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Player;
use App\Entity\MatchGame;
use App\Entity\TournamentFase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MatchGameType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options): void {

    $notNull =  new NotBlank();
    $groups = $options['validation_groups'];

    if (array_intersect(['user', 'admin'], $groups)) {
      $builder
      ->add('winner', IntegerType::class, ['constraints' => [$notNull]])
      ->add('playerA', EntityType::class, ['class' => Player::class, 'constraints' => [$notNull]])
      ->add('playerB', EntityType::class, ['class' => Player::class, 'constraints' => [$notNull]])
      ->add('tournamentFase', EntityType::class, ['class' => TournamentFase::class, 'constraints' => [$notNull]]);
      ;
    }
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => MatchGame::class,
      'allow_extra_fields' => true,
      'csrf_protection' => false,
    ]);
  }
}
