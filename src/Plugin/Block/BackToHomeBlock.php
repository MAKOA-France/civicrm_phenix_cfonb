<?php

// src/Plugin/Block/CustomBlock.php

namespace Drupal\civicrm_phenix_cfonb\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a 'Custom go home  Block' block.
 *
 * @Block(
 *   id = "go_to_home",
 *   admin_label = @Translation("Go to home Block"),
 * )
 */
class BackToHomeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The configuration factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager; // Declare the property here.

  /**
   * Constructs a new NavGroupAndReunionBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager; // Initialize the property here.
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Fetch configuration.
    $current_user = \Drupal::currentUser();
    $user_id = $current_user->id();
    $account = \Drupal\user\Entity\User::load($user_id);
      // Get the user's email address.
    $email = $account->getEmail();
    $c_service = \Drupal::service('civicrm_phenix_cfonb.custom_service');
    
    $is_term_taxo = 'do-not-diplay';
    $current_route_name = \Drupal::routeMatch()->getRouteName();
    $node = \Drupal::routeMatch()->getParameter('node');

    $current_path = \Drupal::service('path.current')->getPath();

 
    if ($current_route_name == 'entity.taxonomy_term.canonical' || $node instanceof \Drupal\node\NodeInterface) {
      $is_term_taxo = 'to-display';
    }
    
    if ($current_path == '/contact/extranet_contactez_nous') {
      $is_term_taxo = 'to-display';
    }
    if ($current_path == '/contact/demande_d_acces_a_l_extranet') {
      $is_term_taxo = 'to-display';
    }
    if (strpos($current_path, '/civicrm-event') !== false) {
      $is_term_taxo = 'to-display';
    }
    if ($current_path == '/communication') {
      $is_term_taxo = 'do-not-diplay';
    }
    
    return [
      '#theme' => 'go_home_block',
      '#cache' => ['max-age' => 0],
      '#content' => [
        'is_term_taxo' => $is_term_taxo,
        'data' => [], // Assuming you have data to pass here.
      ],
    ];
  }

}
