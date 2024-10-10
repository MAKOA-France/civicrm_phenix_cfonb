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
use IntlDateFormatter;
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

  private function getAllEventId () {
    $query = "SELECT
      Event.start_date AS event_start_date,
      civicrm_contact.id AS id,
      Event.id AS event_id, 
      Event.title as event_title
    FROM
      civicrm_contact
    INNER JOIN civicrm_event AS Event ON civicrm_contact.id = Event.created_id
    WHERE
    DATE_FORMAT(
            (Event.start_date  + INTERVAL 7200 SECOND),
            '%Y-%m-%dT%H:%i:%s'
        ) >= DATE_FORMAT(
            (NOW() + INTERVAL 7200 SECOND),
            '%Y-%m-%dT%H:%i:%s'
        )
      -- (DATE_FORMAT((Event.start_date + INTERVAL 7200 SECOND), '%Y-%m-%dT%H:%i:%s') >= DATE_FORMAT((NOW() + INTERVAL 7200 SECOND), '%Y-%m-%dT%H:%i:%s'))
      AND
       (Event.is_active = '1')
    ";

      $results =  \Drupal::database()->query($query)->fetchAll();
      return $results;
  } 


 
  private function checkIfContactIsInsideAGroup ($cid) {

    $allEvent = $this->getAllEventId();
    $contactInsideAgroup = [];
    foreach($allEvent as $event) {
      $event_id = $event->event_id;
      if ($event_id) {

        $events = \Civi\Api4\Event::get(false)
        ->addSelect('rsvpevent_cg_linked_groups.rsvpevent_cf_linked_groups')
        ->addWhere('id', '=', $event_id)
        ->execute();
        if ($events) {
          
          $eventGroupId = $events->getIterator();
          $eventGroupId = iterator_to_array($eventGroupId);  
          $current_user = \Drupal::currentUser();
          $user_roles = $current_user->getRoles();
          foreach ($eventGroupId as $group_id) {
            if ($group_id['rsvpevent_cg_linked_groups.rsvpevent_cf_linked_groups']) {

              $allContactId = \Civi\Api4\GroupContact::get(FALSE)
              ->addSelect('contact_id')
              ->addWhere('group_id', '=', $group_id['rsvpevent_cg_linked_groups.rsvpevent_cf_linked_groups'][0])
              ->execute()->getIterator();
              $allContactId = iterator_to_array($allContactId);  
              $allContactId = array_column($allContactId, 'contact_id');
              
              $authorizedToSeeAllmeet = in_array($cid, $allContactId);
              if (in_array('administrator', $user_roles) || in_array('super_utilisateur', $user_roles) || in_array('permanent', $user_roles)) {
                $authorizedToSeeAllmeet = true;
              }
              
              $contactInsideAgroup[$event_id] = $authorizedToSeeAllmeet;
            }
          }


          
          
        }
      }
    }

    return $contactInsideAgroup;
  }


  public function getAllMeetings ($cid) {
    /* $query = "SELECT
    Event.start_date AS event_start_date,
    civicrm_contact.id AS id,
    Event.id AS event_id, Event.title as event_title
  FROM
    civicrm_contact
  INNER JOIN civicrm_event AS Event ON civicrm_contact.id = Event.created_id
  WHERE
    (DATE_FORMAT((Event.start_date + INTERVAL 7200 SECOND), '%Y-%m-%dT%H:%i:%s') >= DATE_FORMAT(('2023-07-18T22:00:00' + INTERVAL 7200 SECOND), '%Y-%m-%dT%H:%i:%s'))
    AND (Event.is_active = '1') AND civicrm_contact.id = $cid limit 3
  ";
  $results =  \Drupal::database()->query($query)->fetchAll();

  return $results; */

  $isAllowedMeeting = $this->checkIfContactIsInsideAGroup($cid);
    
    // Use the ArrayFilter class to remove false values
    $isAllowedMeeting = $this->removeFalseValues($isAllowedMeeting);
    $isAllowedMeeting = array_keys($isAllowedMeeting);
    if ($isAllowedMeeting) {
      $isAllowedMeeting = implode(', ', $isAllowedMeeting);
      
      $query = "SELECT
    `created_id_civicrm_contact`.`start_date` AS `event_start_date`,
    `created_id_civicrm_contact`.`title`  as event_title,
    `civicrm_contact`.`id` AS `id`,
    `created_id_civicrm_contact`.`id` AS `created_id_civicrm_contact_id`
FROM
    `civicrm_contact`
INNER JOIN
    `civicrm_event` AS `created_id_civicrm_contact` ON `civicrm_contact`.`id` = `created_id_civicrm_contact`.`created_id`
WHERE
    (
        DATE_FORMAT(
            (`created_id_civicrm_contact`.`start_date` + INTERVAL 7200 SECOND),
            '%Y-%m-%dT%H:%i:%s'
        ) >= DATE_FORMAT(
            (NOW() + INTERVAL 7200 SECOND),
            '%Y-%m-%dT%H:%i:%s'
        )
    )
    AND
    (`created_id_civicrm_contact`.`is_active` = '1')  AND `created_id_civicrm_contact`.`id` IN (" . $isAllowedMeeting . ")   ORDER BY
    `event_start_date` ASC limit 3;
";
    $results =  \Drupal::database()->query($query)->fetchAll();
    
  }
   
    return $results;
}

  
public function removeFalseValues($array) {
  return array_filter($array, function ($value) {
      return $value !== false;
  });
}

  public function convertTimesptamToDate($timestamp) {
    $format = 'd.m.y';
    // Create a new DrupalDateTime object using the timestamp.
    $date = DrupalDateTime::createFromTimestamp($timestamp);

    // Format the date using the desired format.
    $formatted_date = $date->format($format);
    return $formatted_date;
  }

  public function getFileSize($file_object) {
    $first_doc_file_url = $this->getNodeFieldValue($file_object, 'uri');
    $first_doc_file_size = filesize($first_doc_file_url);
    return round($first_doc_file_size / 1024, 0);
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

public function getContactIdByEmail ($email) {
  $db = \Drupal::database();
  if ($email) {
    return $db->query("select contact_id from civicrm_email where email = '" . $email . "'")->fetch()->contact_id;
  }
  return false;
}


public function formatDateTo_Y_m_d ($dateString) {
  $formatedDate = new \DateTime($dateString);
  $formatedDate = $formatedDate->format('Y-M-d');
  return $formatedDate;
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
      $months = $dateTime->format('m');
      $jour = $dateTime->format('D');
      setlocale(LC_TIME, 'fr_FR.utf8');
      // Obtient le mois en français
      // $month = strftime('%B', $dateTime->getTimestamp());

      $day_abbreviation = strftime('%a', $dateTime->getTimestamp());
      $day_abbreviation = str_replace('.', '', $day_abbreviation);
      $day_abbreviation = ucfirst($day_abbreviation);

      $dateTime = new \DateTime(); // Your DateTime object here

      // Create an IntlDateFormatter instance
      $formatter = new IntlDateFormatter(
          'fr_FR', // Locale for French; adjust as needed
          IntlDateFormatter::LONG, // Style of date formatting
          IntlDateFormatter::NONE // No need for time formatting
      );

      // Format the month
      $month = $formatter->format($dateTime->getTimestamp());

      // Get the year
      $year = $dateTime->format('Y');
      
      // Get the hour
      $hour = $dateTime->format('H');

      // Get the minute value.
      $minute = $dateTime->format('i');

      return [
          'day' => $day, 
          'month' => $month, 
          'num_month' => $months, 
          'year' => $year,
          'hour' => $hour,
          'minute' => $minute,
          'jour' => $day_abbreviation
      ];
  }

}