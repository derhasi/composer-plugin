<?php

namespace derhasi\Composer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Script\PackageEvent;

/**
 * Class PluginEventLogger
 * @package derhasi\Composer
 */
class PluginEventLogger {

  /**
   * @var \Composer\IO\IOInterface
   */
  protected $io;

  /**
   * @var \Composer\Composer
   */
  protected $composer;

  /**
   * {@inheritdoc}
   */
  public function __construct(Composer $composer, IOInterface $io) {
    $this->io = $io;
    $this->composer = $composer;
  }

  /**
   * Simply log event call.
   *
   * @param \Composer\EventDispatcher\Event $event
   */
  public function logEvent($event) {
    $this->io->write(sprintf('Event called: %s', $event->getName()), TRUE);
  }

  public function logPackageEvent($event) {
    if ($event instanceof PackageEvent) {

      $operation = $event->getOperation();
      if ($operation instanceof InstallOperation) {
        $package = $operation->getPackage();
      }
      elseif ($operation instanceof UpdateOperation) {
        $package = $operation->getTargetPackage();
      }
      elseif ($operation instanceof UninstallOperation) {
        $package = $operation->getPackage();
      }

      if ($package && $package instanceof PackageInterface) {
        /** @var \Composer\Installer\InstallationManager $installationManager */
        $installationManager = $this->composer->getInstallationManager();

        $path = $installationManager->getInstallPath($package);
        $this->io->write(sprintf('Event called: %s, Package: %s, Path: %s', $event->getName(), $package->getName(), $path), TRUE);
      }

    }
    else {
      $this->io->write(sprintf('Event called: %s, <error>no package event</error>', $event->getName()), TRUE);
    }

  }


}