<?php

namespace Drupal\user_links\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Url;

/**
 * Provides a 'UserLinksBlock' block.
 *
 * @Block(
 *  id = "user_links_block",
 *  admin_label = @Translation("User links block"),
 * )
 */
class UserLinksBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;
  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        AccountProxy $current_user
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    if ($this->currentUser->isAuthenticated()) {
      $uid = $this->currentUser->id();
      $links = [
        'user_links_my_account' => [
          'classes' => '',
          'link_attributes' => '',
          'link_classes' => '',
          'title' => t('My account'),
          'title_classes' => '',
          'url' => Url::fromUserInput('/user/' . $uid),
        ],
        'user_links_edit_account' => [
          'classes' => '',
          'link_attributes' => '',
          'link_classes' => '',
          'title' => t('Edit account'),
          'title_classes' => '',
          'url' => Url::fromUserInput('/user/' . $uid . '/edit'),
        ],
        'user_links_edit_logout' => [
          'classes' => '',
          'link_attributes' => '',
          'link_classes' => '',
          'title' => t('Logout'),
          'title_classes' => '',
          'url' => Url::fromUserInput('/user/logout'),
        ],       
      ];
    }
    else {
      $links = [];
    }

    return [
      '#theme' => 'user_links',
      '#links' => $links,
      '#cache' => [
        'contexts' => ['user'],
      ],
    ];
  }

}
