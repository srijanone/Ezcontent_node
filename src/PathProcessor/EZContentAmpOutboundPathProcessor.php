<?php

namespace Drupal\ezcontent_node\PathProcessor;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

/**
 * Class EZContentAmpOutboundPathProcessor.
 *
 * Handles transition between AMP pages.
 *
 * @package Drupal\ezcontent_node\PathProcessor
 */
class EZContentAmpOutboundPathProcessor implements OutboundPathProcessorInterface {

  /**
   * Module Handler service.
   *
   * @var Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs an EZContentAmpOutboundPathProcessor object.
   *
   * @param Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler service.
   */
  public function __construct(ModuleHandlerInterface $moduleHandler) {
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  public function processOutbound($path, &$options = [], ?Request $request = NULL, ?BubbleableMetadata $bubbleable_metadata = NULL): string {
    // Check if AMP module exists.
    if ($this->moduleHandler->moduleExists('amp')) {
      // Get the current route using routeMatch().
      $routeMatch = \Drupal::routeMatch();
      $isAmpRoute = \Drupal::service('router.amp_context')->isAmpRoute();
      // Check if the route is an AMP route and adjust the query accordingly.
      if (isset($options['route']) && $options['route'] instanceof Route) {
        if ($routeMatch->getRouteName() == '<front>' || $routeMatch->getRouteName() == 'entity.node.canonical') {
          if ($isAmpRoute) {
            $options['query']['amp'] = TRUE;
          }
        }
      }
    }
    return $path;
  }

}
