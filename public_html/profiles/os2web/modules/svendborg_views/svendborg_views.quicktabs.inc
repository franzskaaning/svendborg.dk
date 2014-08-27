<?php
/**
 * @file
 * svendborg_views.quicktabs.inc
 */

/**
 * Implements hook_quicktabs_default_quicktabs().
 */
function svendborg_views_quicktabs_default_quicktabs() {
  $export = array();

  $quicktabs = new stdClass();
  $quicktabs->disabled = FALSE; /* Edit this to true to make a default quicktabs disabled initially */
  $quicktabs->api_version = 1;
  $quicktabs->machine_name = 'nyhed_quicktabs';
  $quicktabs->ajax = 0;
  $quicktabs->hide_empty_tabs = FALSE;
  $quicktabs->default_tab = 0;
  $quicktabs->title = 'Nyhed quicktabs';
  $quicktabs->tabs = array(
    0 => array(
      'vid' => 'svendborg_news_view',
      'display' => 'block_1',
      'args' => 'udbud',
      'title' => 'Udbud',
      'weight' => '-100',
      'type' => 'view',
    ),
    1 => array(
      'vid' => 'culture_calendar',
      'display' => 'block',
      'args' => 'Kultur-kalender',
      'title' => 'Kultur-kalender',
      'weight' => '-99',
      'type' => 'view',
    ),
    2 => array(
      'vid' => 'svendborg_news_view',
      'display' => 'block_1',
      'args' => 'Høring',
      'title' => 'Høring',
      'weight' => '-98',
      'type' => 'view',
    ),
    3 => array(
      'vid' => 'svendborg_news_view',
      'display' => 'block_1',
      'args' => 'Byrådsmøde',
      'title' => 'Byrådsmøde',
      'weight' => '-97',
      'type' => 'view',
    ),
  );
  $quicktabs->renderer = 'quicktabs';
  $quicktabs->style = 'nostyle';
  $quicktabs->options = array();

  // Translatables
  // Included for use with string extractors like potx.
  t('Byrådsmøde');
  t('Høring');
  t('Kultur-kalender');
  t('Nyhed quicktabs');
  t('Udbud');

  $export['nyhed_quicktabs'] = $quicktabs;

  return $export;
}
