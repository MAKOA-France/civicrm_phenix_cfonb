<?php

declare(strict_types=1);

namespace Drupal\civicrm_phenix_cfonb\Plugin\Block;
use Drupal\views\Views;

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
    

    // Load the view.
    $view = Views::getView('contacts_etablissement');

    $data = [];
    if ($view) {
      // Set the display ID (e.g., "page_1", "block_1").
      $view->setDisplay('block_2');

      // Optionally, set arguments if the view uses contextual filters.
      // $view->setArguments(['arg1', 'arg2']); // Replace with actual arguments.

      // Execute the view query.
      $view->execute();

      // Get the results.
      $results = $view->result;
      if ($results) {
        $data['id'] = $results[0]->civicrm_contact_employer_id;
        $data['hash'] = $results[0]->civicrm_contact_hash;
      }
      
    }
    
    return [
      '#theme' => 'block_custom_navigation',
      '#cache' => ['max-age' => 0],
      '#content' => [
        'data' => $data,
      ],
    ];
  }

}
