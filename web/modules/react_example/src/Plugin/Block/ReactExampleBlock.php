<?php

namespace Drupal\react_example\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a react example block.
 *
 * @Block(
 *   id = "react_example_react_example",
 *   admin_label = @Translation("React example"),
 *   category = @Translation("React example")
 * )
 */
class ReactExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'initial_name' => $this->t('Drupal'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['initial_name'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Initial name'),
      '#default_value' => $this->configuration['initial_name'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['initial_name'] = $form_state->getValue('initial_name');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'react_example',
      '#id' => uniqid(),
      '#initial_name' => $this->configuration['initial_name'],
      '#attached' => [
        'library' => [
          'react_example/react_example'
        ],
      ],
    ];
  }

}
