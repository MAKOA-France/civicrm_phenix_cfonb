<?php

namespace Drupal\phenix_cfonb_group\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\FilterPluginBase;

/**
 * Filter content.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("views_filter_last_access")
 */
class PhaseViewsFilter extends FilterPluginBase {
  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {

   
    $form['value'] = [
      '#type' => 'select',
      '#title' => $this->t('Dernier accès'),
      '#options' => [
        1 => $this->t('Le dernier accès date de plus de 1 mois'),
        2 => $this->t('Le dernier accès date de plus de 2 mois'),
        3 => $this->t('Le dernier accès date de plus de 3 mois'),
      ],
      '#default_value' => $this->value,
    ];
    // dump($form);
    $form['test']['#weight'] = 900000;


    //Envoi d'email 
    $sitename = \Drupal::config('system.site')->get('name');
    $langcode = \Drupal::config('system.site')->get('langcode');
    $module = 'phenix_cfonb_group';
    $key = 'custom_mail_key';

    $subject = "CFONB - INACTIVITE PENDANT PLUS DE 3 MOIS";
    $reply = NULL;
    $send = TRUE;

    $params['message'] = "";
    $params['subject'] = t($subject);
    $params['options'] = [
      'username' => "CFONB", // TODO: faire du ménage // $account->getUsername();
      'title' => t('Pushmail CFONB'),
      'test' => 'test',
      //'footer' => t($footer),
    ];

    $params['cc'] = 'sitraka@makoa.fr';
    $params['options']['uid'] = 1812;
    $params['options']['user_mail'] = 'sitraka@makoa.fr';
    $params['options']['user_name'] = 'sitraka';

    $mailManager = \Drupal::service('plugin.manager.mail');

    // $result = $mailManager->mail('makoad8pushmail', $key, 'sitraka@makoa.fr', $langcode, $params, NULL, $send); // POUR TESTS

    $form['test']['#markup'] = '<p data-all-id="" class=" link-to-send-mail btn btn-primary" >Envoyer un courrie aux utilisateurs selectionnés</p>';
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // $this->ensureMyTable();
    // dump($this->query);
    // $boolean_1 = 'field_boolean_1';
    // $boolean_2 = 'field_boolean_2';
    // $operator = '=';

    // $this->query->addField("node__{$boolean_1}", "{$boolean_1}_value");
    // $this->query->addField("node__{$boolean_2}", "{$boolean_2}_value");
    // $boolean_1_value = NULL;
    // $boolean_2_value = NULL;

    $custom_service = \Drupal::service('phenix_cfonb_group.custom_service');
    $timestampThreeMonthAgo = $custom_service->timestampThreeMonth();
    $timestampTwoMonth = $custom_service->timestampTwoMonth();
    $timestampOneMonth = $custom_service->timestampOneMonth();
    $timestamp = '';

     switch ($this->value[0]) {
      case '1':
        $timestamp = $timestampOneMonth;
        break;
      case '2':
        $timestamp = $timestampTwoMonth;
        break;
      case '3':
        $timestamp = $timestampThreeMonthAgo;
        break;
      case 'neither':
        //do other stuff
        break;
    } 

    
    if ($timestamp) {
      $this->query->addWhere($this->options['group'], "users_field_data.access", $timestamp, '<');
    }
    // $this->query->addWhere($this->options['group'], "node__{$boolean_2_value}.{$boolean_2_value}_value", $boolean_2_value, '=');
  }

}