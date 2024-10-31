<?php

declare(strict_types=1);

namespace Drupal\civicrm_phenix_cfonb\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block home adherent navigation block.
 *
 * @Block(
 *   id = "civicrm_phenix_cfonb_block_home_adherent_navigation",
 *   admin_label = @Translation("Block home adherent navigation"),
 *   category = @Translation("Custom"),
 * )
 */
final class BlockHomeAdherentNavigationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    
    
    return [
      '#theme' => 'block_custom_navigation',
      '#cache' => ['max-age' => 0],
      '#content' => [
        'data' => $data,
      ],
    ];
  }

}
