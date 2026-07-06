<?php

namespace Drupal\drupal_sample_helper\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Drupal Sample Helper settings for this site.
 */
final class SampleSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'drupal_sample_helper_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['drupal_sample_helper.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('drupal_sample_helper.settings');

    $form['welcome_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Welcome Message'),
      '#default_value' => $config->get('welcome_message') ?? 'こんにちは！Drupalの世界へようこそ。これはカスタムモジュールから出力されたメッセージです。',
      '#description' => $this->t('ようこそページやカスタムブロックに表示するメッセージを入力してください。'),
      '#required' => TRUE,
    ];

    $form['show_date'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show today\'s date'),
      '#default_value' => $config->get('show_date') ?? TRUE,
      '#description' => $this->t('ブロックに今日の日付を表示するかどうか。'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('drupal_sample_helper.settings')
      ->set('welcome_message', $form_state->getValue('welcome_message'))
      ->set('show_date', $form_state->getValue('show_date'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
