<?php

namespace Drupal\drupal_sample_helper\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a welcome block.
 */
#[Block(
  id: "drupal_sample_welcome_block",
  admin_label: new TranslatableMarkup("Drupal Sample Welcome Block"),
  category: new TranslatableMarkup("Custom")
)]
final class SampleWelcomeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * Constructs a new SampleWelcomeBlock instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $config = $this->configFactory->get('drupal_sample_helper.settings');
    $message = $config->get('welcome_message') ?? $this->t('Welcome to Drupal!');
    $show_date = $config->get('show_date') ?? TRUE;

    $build = [
      '#type' => 'container',
      'message' => [
        '#markup' => '<p><strong>' . htmlspecialchars($message) . '</strong></p>',
      ],
    ];

    if ($show_date) {
      $build['date'] = [
        '#markup' => '<p>' . $this->t('今日の日付: @date', ['@date' => date('Y-m-d')]) . '</p>',
      ];
    }

    // キャッシュタグを追加して、設定が更新されたらブロックも更新されるようにする
    $build['#cache'] = [
      'tags' => ['config:drupal_sample_helper.settings'],
    ];

    return $build;
  }

}
