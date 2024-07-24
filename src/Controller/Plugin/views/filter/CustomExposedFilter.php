<?php

namespace Drupal\phenix_cfonb_group\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\StringFilter;
use Drupal\views\ViewExecutable;

/**
 * Exposed filter for custom functionality.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("custom_exposed_filter")
 */
class CustomExposedFilter extends StringFilter {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, $display_id, array &$options = NULL) {
    parent::init($view, $display_id, $options);
    $this->definition['options callback'] = array($this, 'generateOptions');
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    if (!empty($this->value)) {
      $this->query->addWhere(0, $this->options['field'], $this->value, '=');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function exposeForm($form, &$form_state) {
    $form = parent::exposeForm($form, $form_state);

    // Customize the exposed form as needed.
    // Example: Change the label of the exposed filter.
    $form['value']['#title'] = $this->t('Custom Filter Label');

    return $form;
  }

}
