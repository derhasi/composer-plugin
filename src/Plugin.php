<?php

/**
 * @file
 * Contains derhasi\Composer\Plugin.
 */

namespace derhasi\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;

/**
 * Class Plugin.
 */
class Plugin implements PluginInterface, EventSubscriberInterface {

  /**
   * @var \derhasi\Composer\PluginEventLogger
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    // The events are called by our separate logger class. This way we separate
    // functionality and also avoid some debug issues with the plugin being
    // copied on initialisation.
    // @see \Composer\Plugin\PluginManager::registerPackage()
    $this->logger = new PluginEventLogger($composer, $io);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return array(
      PluginEvents::PRE_FILE_DOWNLOAD => 'logEvent',
      PluginEvents::COMMAND => 'logEvent',
      ScriptEvents::PRE_PACKAGE_INSTALL => 'logPackageEvent',
      ScriptEvents::POST_PACKAGE_INSTALL => 'logPackageEvent',
      ScriptEvents::PRE_PACKAGE_UPDATE => 'logPackageEvent',
      ScriptEvents::POST_PACKAGE_UPDATE => 'logPackageEvent',
      ScriptEvents::PRE_PACKAGE_UNINSTALL => 'logPackageEvent',
      ScriptEvents::POST_PACKAGE_UNINSTALL => 'logPackageEvent',
    );
  }

  /**
   * Simply log event call.
   *
   * @param \Composer\EventDispatcher\Event $event
   */
  public function logEvent($event) {
    $this->logger->logEvent($event);
  }

  public function logPackageEvent($event) {
    $this->logPackageEvent($event);
  }

}
