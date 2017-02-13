<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all envrionments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to insure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}
$settings['install_profile'] = 'westminster';
$databases['default']['default'] = array (
  'database' => 'westminster_drops_8',
  'username' => 'root',
  'password' => 'root',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);
$settings['hash_salt'] = 'w1lS6DiwEdBAmbu_g44NGrx_IjjOKjOn4bzg-S2fEs2Rr4OkUCPY_WzAFsOTmU_bxy46TU6rWw';

$schemes = [
  's3' => [
    'driver' => 's3',
    'config' => [
      'key'     => 'AKIAJBCNWGVBTR3QUG2A',
      'secret'  => '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',
      'region'  => 'us-east-1',
      'bucket'  => 's3test.zebradog.com',

      'cors' => TRUE,                          // Set to TRUE if CORS upload
                                                  // support is enabled for the
                                                  // bucket.
    ],

    'cache' => TRUE, // Creates a metadata cache to speed up lookups.
  ],
];

$settings['flysystem'] = $schemes;
