<?php

namespace Drupal\drupal_sample_helper\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns response for the welcome page.
 */
final class SamplePageController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function content(): array {
    // ControllerBase の config() メソッドを使用して設定を取得する
    $config = $this->config('drupal_sample_helper.settings');
    $message = $config->get('welcome_message') ?? 'こんにちは！Drupalの世界へようこそ。これはカスタムモジュールから出力されたメッセージです。';

    return [
      '#type' => 'container',
      'welcome_title' => [
        '#markup' => '<h2>' . $this->t('カスタムモジュールからのご挨拶') . '</h2>',
      ],
      'welcome_body' => [
        '#markup' => '<div class="welcome-message">' . htmlspecialchars($message) . '</div>',
      ],
      'instructions' => [
        '#markup' => '<p style="margin-top:20px; font-size: 0.9em; color: #555;">' . 
          $this->t('※このメッセージは、管理画面の <a href="@config_url">設定フォーム</a> から変更できます。', [
            '@config_url' => '/admin/config/services/drupal-sample-helper'
          ]) . '</p>',
      ],
      '#cache' => [
        'tags' => ['config:drupal_sample_helper.settings'],
      ],
    ];
  }

}
