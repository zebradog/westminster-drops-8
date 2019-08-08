<?php

namespace Drupal\westminster_schedule\ServiceProvider;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\node\Entity\Node;

/**
 * A standard service that exposes scheduling data for other modules.
 */
class Schedule extends ServiceProviderBase
{

  /**
   * Retrieves all currently scheduled entities.
   *
   * @return Node[] - `scheduled_content_2` nodes
   */
  public function getCurrentlyScheduled() : array
  {
    $now = new DrupalDateTime('now', $this->getTimezone());
    $now = $now->format(DATETIME_DATETIME_STORAGE_FORMAT);

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'scheduled_content_2')
      ->condition('field_schedule_start_time', $now, '<=')
      ->condition('field_schedule_end_time', $now, '>=')
      ->sort('field_schedule_start_time', 'DESC')
      ->accessCheck(false)
    ;

    return Node::loadMultiple($query->execute());
  }

  /**
   * Retrieves the first currently scheduled item having $type.
   *
   * @param string $type - The machine name of the desired node type
   * @return Node|null
   */
  public function getCurrentlyScheduledItem(string $type) : ?Node
  {
    foreach ($this->getCurrentlyScheduledItems() as $entity) {
      if ($entity->bundle() == $type) {
        return $entity;
      }
    }

    return null;
  }

  /**
   * Retrieves all currently scheduled items.
   *
   * @return Node[]
   */
  public function getCurrentlyScheduledItems() : array
  {
    $scheduleEntities = $this->getCurrentlyScheduled();

    $entityIds = array_map(function ($entity) {
      return $entity->get('field_scheduled_item')->target_id;
    }, $scheduleEntities);

    return Node::loadMultiple($entityIds);
  }

  /**
   * Returns the default timezone specified within the regional settings (i.e. /admin/config/regional/settings).
   * For best results, this should be configured to reflect the physical location of the client.
   */
  protected function getTimezone() : \DateTimeZone
  {
    $config = \Drupal::config('system.date');
    $defaultTimezone = $config->get('timezone.default');

    return new \DateTimeZone($defaultTimezone ?: @date_default_timezone_get());
  }

}
