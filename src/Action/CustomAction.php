<?php

namespace Drupal\civicrm_phenix_cfonb\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;

/**
 * Provides a custom VBO action.
 *
 * @Action(
 *   id = "civicrm_phenix_cfonb_cus",
 *   label = @Translation("Custom VBO Action for media"),
 *   type = "node"  // Change to the entity type you're targeting, e.g., 'user', 'taxonomy_term'.
 * )
 */
class CustomAction extends ViewsBulkOperationsActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    // Add your custom logic here.
    if ($entity) {
      // For example, updating a field value.
      $entity->set('field_document_reserve_aux_adher', 1); // Unpublish a node, for instance.
      $entity->save();
    }
    return $this->t('Processed entity: @title', ['@title' => $entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    // Define access control for the action.
    return $object->access('update', $account, $return_as_object);
  }
}
