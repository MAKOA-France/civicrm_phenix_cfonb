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
 

}