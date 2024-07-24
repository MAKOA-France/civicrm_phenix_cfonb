<?php

namespace Drupal\phenix_cfonb_group\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a custom action two.
 *
 * @Action(
 *   id = "custom_view_action_two",
 *   label = @Translation("Custom View Action Two"),
 *   type = "node"
 * )
 */
class CustomViewActionTwo extends ActionBase implements ContainerFactoryPluginInterface {

  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  public function execute($entity = NULL) {
    \Drupal::messenger()->addMessage(t('Custom action two executed on @title.', ['@title' => $entity->label()]));
  }

  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $access = $object->access('update', $account, TRUE);
    return $return_as_object ? $access : $access->isAllowed();
  }
}
