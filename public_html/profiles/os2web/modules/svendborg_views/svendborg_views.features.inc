<?php
/**
 * @file
 * svendborg_views.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function svendborg_views_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "quicktabs" && $api == "quicktabs") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function svendborg_views_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_image_default_styles().
 */
function svendborg_views_image_default_styles() {
  $styles = array();

  // Exported image style: svendborg_frontpage_news_large.
  $styles['svendborg_frontpage_news_large'] = array(
    'name' => 'svendborg_frontpage_news_large',
    'label' => 'Svendborg frontpage news large',
    'effects' => array(
      8 => array(
        'label' => 'Skalér og beskær',
        'help' => 'Skalering og beskæring bevarer billedets højde-bredde-forhold og beskærer derefter den største dimension. Det er nyttigt til at generere kvadratiske thumbnails uden at forvrænge billedet.',
        'effect callback' => 'image_scale_and_crop_effect',
        'dimensions callback' => 'image_resize_dimensions',
        'form callback' => 'image_resize_form',
        'summary theme' => 'image_resize_summary',
        'module' => 'image',
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 630,
          'height' => 260,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: svendborg_frontpage_news_small.
  $styles['svendborg_frontpage_news_small'] = array(
    'name' => 'svendborg_frontpage_news_small',
    'label' => 'Svendborg frontpage news small',
    'effects' => array(
      9 => array(
        'label' => 'Skalér og beskær',
        'help' => 'Skalering og beskæring bevarer billedets højde-bredde-forhold og beskærer derefter den største dimension. Det er nyttigt til at generere kvadratiske thumbnails uden at forvrænge billedet.',
        'effect callback' => 'image_scale_and_crop_effect',
        'dimensions callback' => 'image_resize_dimensions',
        'form callback' => 'image_resize_form',
        'summary theme' => 'image_resize_summary',
        'module' => 'image',
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 350,
          'height' => 145,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}
