<?php

declare(strict_types=1);

namespace Drupal\civicrm_phenix_cfonb\Plugin\Block;
use Drupal\views\Views;
use Drupal\Core\Database\Database;
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



      $current_user = \Drupal::currentUser();

      // Get the user ID.
      $current_user_id = $current_user->id();

      // $userHasMeet = $this->checkifhasmeet($current_user_id);
      
    }
    
    return [
      '#theme' => 'block_custom_navigation',
      '#cache' => ['max-age' => 0],
      '#content' => [
        'data' => $data,
      ],
    ];
  }
  
  private function checkifhasmeet ($current_user_id) {
    // $ = 1; // Example value for testing.
$event_date = '2024-12-03T23:00:00'; // Example value for testing.

$date = \Drupal\Core\Datetime\DrupalDateTime::createFromFormat('Y-m-d H:i:s', $date_string);

// Format the date
$formatted_date = $date->format('Y-m-d\TH:i:s');



$query = "SELECT 
contact_id_civicrm_contact.id AS contact_id_civicrm_contact_id, 
civicrm_event_civicrm_participant.id AS civicrm_event_civicrm_participant_id, 
civicrm_event_civicrm_participant.title AS civicrm_event_civicrm_participant_title, 
civicrm_event_civicrm_participant.start_date AS civicrm_event_civicrm_participant_start_date, 
MIN(civicrm_contact.id) AS id, 
MIN(contact_id_civicrm_contact.id) AS contact_id_civicrm_contact_id_1, 
MIN(civicrm_event_civicrm_participant.id) AS civicrm_event_civicrm_participant_id_1, 
MIN(users_field_data_civicrm_uf_match.uid) AS users_field_data_civicrm_uf_match_uid, 
MIN(created_id_civicrm_contact.id) AS created_id_civicrm_contact_id
FROM 
civicrm_contact AS civicrm_contact
INNER JOIN 
cfonbmakoa6sym_0.civicrm_participant AS contact_id_civicrm_contact 
ON civicrm_contact.id = contact_id_civicrm_contact.contact_id
INNER JOIN 
cfonbmakoa6sym_0.civicrm_event AS civicrm_event_civicrm_participant 
ON contact_id_civicrm_contact.event_id = civicrm_event_civicrm_participant.id
INNER JOIN 
cfonbmakoa6sym_0.civicrm_uf_match AS civicrm_uf_match 
ON civicrm_contact.id = civicrm_uf_match.contact_id
INNER JOIN 
cfonbmakoa6sym_0.users_field_data AS users_field_data_civicrm_uf_match 
ON civicrm_uf_match.uf_id = users_field_data_civicrm_uf_match.uid
LEFT JOIN 
cfonbmakoa6sym_0.civicrm_event AS created_id_civicrm_contact 
ON civicrm_contact.id = created_id_civicrm_contact.created_id
WHERE 
users_field_data_civicrm_uf_match.uid = ".$current_user_id."
AND (

  DATE_FORMAT((civicrm_event_civicrm_participant.start_date + INTERVAL 3600 SECOND), '%Y-%m-%d\T%H:%i:%s') > DATE_FORMAT(('2024-12-03T23:00:00' + INTERVAL 3600 SECOND), '%Y-%m-%d\T%H:%i:%s')


) 
AND civicrm_event_civicrm_participant.is_active = 1
GROUP BY 
contact_id_civicrm_contact_id, 
civicrm_event_civicrm_participant_id, 
civicrm_event_civicrm_participant_title, 
civicrm_event_civicrm_participant_start_date
ORDER BY 
civicrm_event_civicrm_participant_start_date ASC
LIMIT 7 OFFSET 0;";







     $rows = \Drupal::database()->query($query)->fetchAll();
  dump($rows);

  }
}
