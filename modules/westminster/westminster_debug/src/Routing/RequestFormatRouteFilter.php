<?php

  namespace Drupal\westminster_debug\Routing;

  use Drupal\Core\Routing\RequestFormatRouteFilter as RequestFormatRouteFilterBase;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\RouteCollection;

  /**
   * Fixes #2937942 introduced in 8.5.0.
   *
   * We noticed that routes to our REST exports began returning an HTTP 406
   * error when accessed without the `_format` parameter. Previously we thought
   * this was optional, so it is omitted from a lot of our legacy code.
   * This new requirement breaks everything.
   *
   * When a route requires the `_format` parameter (e.g. a REST export) and
   * only has one accepted format, requests for that route will now be
   * rewritten to use that format.
   *
   * @link https://www.drupal.org/project/drupal/issues/2937942
   * @todo Remove when issue is resolved in core.
   */
  Class RequestFormatRouteFilter extends RequestFormatRouteFilterBase {

    /**
     * {@inheritdoc}
     */
    public function filter(RouteCollection $collection, Request $request) {
      $defaultFormat = static::getDefaultFormat($collection);
      $requestFormat = $request->getRequestFormat($defaultFormat);

      if ($defaultFormat !== $requestFormat) {
        foreach ($collection as $name => $route) {

          if ($route->hasRequirement('_format')) {
            $routeFormats = array_filter(
              explode('|', $route->getRequirement('_format'))
            );

            if ($routeFormats === [$defaultFormat]) {
              $request->setRequestFormat($defaultFormat);
            }
          }

        }
      }

      return $collection;
    }

  }
