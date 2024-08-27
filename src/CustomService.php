<?php

namespace Drupal\civicrm_phenix_cfonb;

use Drupal\taxonomy\Entity\Term;
use \Drupal\media\Entity\Media;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Url;
use Drupal\Core\Link;
use \Drupal\Component\Utility\UrlHelper;

/**
 * Class PubliciteService
 * @package Drupal\civicrm_phenix_cfonb\Services
 */
class CustomService {

     /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

//   const MY_CONST = 111;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, ConfigFactoryInterface $configFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->configFactory = $configFactory;
  }

  
  private function newDateTime() {
    return new \DateTime();
 }

 public function timestampOneMonth() {
    $currentDate = new \DateTime();

    // Soustraire un mois
    $currentDate->modify('-1 month');

   //  dump($currentDate, ' UN mois');
    // Obtenir le timestamp
    return $timestamp = $currentDate->getTimestamp();
 }
 public function customizeViewReunionOfTheCommissionPage(&$var) {
  $view = $var['view'];
  $field = $var['field'];
  $requests = \Drupal::request();
  $row = $var['row'];

  if ($field->field == 'title' ) {
    $current_id = $var['row']->id;
    $start_date = $row->civicrm_event_start_date;
    $start_date = $this->formatDateWithMonthInLetterAndHours($start_date);
    $value = $field->getValue($row);
    $classOddAndEven = 'odd';
    if ($view->current_display == 'block_1') {
      $classOddAndEven = 'even';
    }

    $var['output'] = [
      '#theme' => 'civicrm_phenix_cfonb_alter_view_detail_commission_reunion',
      '#cache' => ['max-age' => 0],
      '#content' => [
        'start_date' => $start_date,
        'event_id' => $current_id,
        'class_odd_even' => $classOddAndEven,
        'title' => $value
      ]
    ];
  }
}

      
public function getTypeDocument ($media) {
  if (!$media) {
    return null;
  }
  $type_doc = '';
  $type_doc_value = $this->getNodeFieldValue($media, 'field_type_de_document');
  if ($type_doc_value != 18) {
    $type_doc = $media->get('field_type_de_document')->getFieldDefinition()->getItemDefinition()->getSettings()['allowed_values'][$type_doc_value];
  }
  return $type_doc;
}

public function getNodeFieldValue ($node, $field) {
  $value = '';
  $getValue = $node->get($field)->getValue();
  if (!empty($getValue)) {
    if (isset($getValue[0]['target_id'])) { //For entity reference (img / taxonomy ...)
      $value = $getValue[0]['target_id'];
    }elseif (isset($getValue[0]['value']))  { //For simple text / date
      $value = $getValue[0]['value'];
    }else if(isset($getValue[0]['uri'])) {
      $value = $getValue[0]['uri'];
    }else { //other type of field
      $value = $getValue['x-default'];
    }
  }
  return $value;
}

/**
     * Permet de récupérer le jour/mois/année heure:minute
     * @return array()
     */
    public function formatDateWithMonthInLetterAndHours ($start_date) {
      // Create a DateTime object from the date string
      $dateTime = new \DateTime($start_date);
  
      // Get the day
      $day = $dateTime->format('d');

      // Get the month
      $month = $dateTime->format('m');
      // Obtient le mois en français
      setlocale(LC_TIME, 'fr_FR.utf8');
      $month = strftime('%B', $dateTime->getTimestamp());

      // Get the year
      $year = $dateTime->format('Y');
      
      // Get the hour
      $hour = $dateTime->format('H');

      // Get the minute value.
      $minute = $dateTime->format('i');

      return [
          'day' => $day, 
          'month' => $month, 
          'year' => $year,
          'hour' => $hour,
          'minute' => $minute
      ];
  }

}