<?php


namespace Drupal\civicrm_phenix_cfonb\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Provides a Cfonb phenix custom module form.
 */
class TextBienvenuHome extends ConfigFormBase {


    /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['civicrm_phenix_cfonb_text.settings'];
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'civicrm_phenix_cfonb_text_bienvenu_home';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('civicrm_phenix_cfonb_text.settings');
    $form['text_bienvenu'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Texte de bienvenue'),
      '#format' => 'full_html', // Load the saved format
      '#allowed_formats' => ['full_html'], // Specify allowed formats
      '#allowed_tags' => ['iframe', 'h1', 'p', 'strong', 'em', 'h2', 'h3', 'span', 'img', 'ul', 'li'],
      '#default_value' => $config->get('text_bienvenu')['value']
    ];
    // Add a submit button
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary', // Optional: style it as a primary button
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('civicrm_phenix_cfonb_text.settings')
      ->set('text_bienvenu', $form_state->getValue('text_bienvenu'))
      ->save();
  }

  
  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    // Disable cache tags by returning an empty array.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    // Disable cache contexts by returning an empty array.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    // Disable caching by setting the max age to 0.
    return 0;
  }

}
