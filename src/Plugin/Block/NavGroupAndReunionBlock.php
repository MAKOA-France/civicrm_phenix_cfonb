<?php

// src/Plugin/Block/CustomBlock.php

namespace Drupal\civicrm_phenix_cfonb\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a 'Custom Nav meeting and group Block' block.
 *
 * @Block(
 *   id = "meeting_and_group_nav",
 *   admin_label = @Translation("Navigation meeting and group Block"),
 * )
 */
class NavGroupAndReunionBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    $cid = $user_id ? $c_service->getContactIdByEmail($email) : $getId;
    $all_meetings = $c_service->getAllMeetings($cid);

    $all_groups = $c_service->getAllMyGroup($cid);

    $all_comm = $c_service->getAllinfoCom($cid);

    foreach ($all_meetings as $meet) {
      $formated_date = $c_service->formatDateWithMonthInLetterAndHours ($meet->civicrm_event_civicrm_participant_start_date);
      // dump($formated_date);
      $hour = isset($formated_date['hour']) ? ' | ' . $formated_date['hour'] . ':' . $formated_date['minute'] : '';
      $meet->formated_date = $formated_date['day_letter'] . '  ' . $formated_date['day']  . '/' . $formated_date['num_month'] . '/' . $formated_date['year'] . $hour;
      // $linked_group = $burger_service->getLinkedGroupWithEvent ($meet->event_id); 
      // $meet->linked_group = $linked_group;
    }

    $is_term_taxo = false;
    $current_route_name = \Drupal::routeMatch()->getRouteName();
    $node = \Drupal::routeMatch()->getParameter('node');
    // dump($current_route_name, \Drupal::request());
    if ($current_route_name == 'view.contacts_etablissement.page_1') {
      $is_term_taxo = true;
    }
    if ($current_route_name == 'entity.contact_form.canonical') {
      $is_term_taxo = true;
    }

    if (in_array($current_route_name, ['entity.taxonomy_term.canonical', 'entity.civicrm_event.canonical']) || $node instanceof \Drupal\node\NodeInterface) {
      $is_term_taxo = true;
    }
    
    // You can now use $this->entityTypeManager for entity operations.
    if (!$cid) {
      return;
    }
    return [
      '#theme' => 'nav_group_and_meeting',
      '#cache' => ['max-age' => 0],
      '#content' => [
        'data' => [], // Assuming you have data to pass here.
        'meet' => $all_meetings,
        'groupes' => $all_groups,
        'all_com' => $all_comm,
        'is_term' => $is_term_taxo
      ],
    ];
  }

}
