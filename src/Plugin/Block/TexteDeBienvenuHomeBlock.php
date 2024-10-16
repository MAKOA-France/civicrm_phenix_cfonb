<?php

declare(strict_types=1);

namespace Drupal\civicrm_phenix_cfonb\Plugin\Block;


use Drupal\Core\Block\BlockBase;


/**
 * Provides a texte de bienvenu home block.
 *
 * @Block(
 *   id = "civicrm_phenix_cfonb_texte_de_bienvenu_home",
 *   admin_label = @Translation("Texte de bienvenu home"),
 *   category = @Translation("Custom"),
 * )
 */
class TexteDeBienvenuHomeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {

    $text = \Drupal::config('civicrm_phenix_cfonb_text.settings')->get('text_bienvenu')['value'];
    $build['content'] = [
      '#markup' => $text,
    ];
    return $build;
  }

}
