<?php 

namespace Drupal\phenix_cfonb_group\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @ViewsField("custom_field_1")
 */
class CustomField extends FieldPluginBase {
  
  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    // Fetch data from $values or perform custom logic.
    // dump('tqsdf');
    // $output = 'Custom Data deeeeh'; // Replace with your logic to generate the output.
    // $id = $values->_entity->get('uid')->getValue()[0]['value'];
    // $email = $values->_entity->get('mail')->getValue()[0]['value'];
    // $cid = $this-getContactIdByEmail($email);
    $cid = $this->getContactIdByEmail($values->_entity->get('mail')->getValue()[0]['value']);
    $employer = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('employer_id.display_name')
      ->addWhere('id', '=', $cid)
      ->execute()->first()['employer_id.display_name'];
    return [
      '#markup' => '<p class="employer-name">' . $employer . '</p>',
    ];
  }
  
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    // Define additional options here if necessary.
    return $options;
  }

  public function getContactIdByEmail ($email) {
    $db = \Drupal::database();
    if ($email) {
      return $db->query("select contact_id from civicrm_email where email = '" . $email . "'")->fetch()->contact_id;
    }
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    // Add custom field settings here if necessary.
  }
}
