<?php
/**
 * @file
 * template.php
 */

/**
 * Implements template_preprocess_page().
 */
function svendborg_theme_preprocess_page(&$variables) {
  // Remove all Taxonomy auto listings here.
  $term = NULL;
  if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
    $term = taxonomy_term_load(arg(2));
    $term_name = $term->vocabulary_machine_name;
    unset($variables['page']['content']['system_main']['no_content']);
    // There will not be nodes and other normal term content on terms "os2web_base_tax_site_structure" pages.
    if ($term_name == "os2web_base_tax_site_structure") {
      unset($variables['page']['content']['system_main']['nodes']);
      unset($variables['page']['content']['system_main']['pager']);
    }
    else {
      // On Other term pages, there will be a view with nodes.
      $view = views_get_view('taxonomy_term');
      $view->set_display('block_1');
      $view->set_arguments(array(arg(2)));
      $view->set_items_per_page(20);
      $view->pre_execute();
      $view->execute();
      $variables['page']['content']['system_main'] = array('#markup' => '<h2>' .$term->name .'</h2>' . $view->render());
    }

    // Variable that defines that this term is the top of the hieraki.
    $term_is_top = _svendborg_theme_term_is_top($term->tid);
    // Get wether this is a top term, and provide a variable for the templates.
    $variables['page']['term_is_top'] = $term_is_top;
  }

  $node = NULL;
  if (isset($variables['node']) && !empty($variables['node']->nid)) {
    $node = $variables['node'];
  }
  $sidebar_second_hidden = FALSE;
  $sidebar_first_hidden = FALSE;

  // If node has hidden the sidebar, set content to null.
  if ($node && $hide_sidebar_field = field_get_items('node', $node, 'field_svendborg_hide_sidebar')) {
    if ($hide_sidebar_field[0]['value'] == '1') {
      $variables['page']['sidebar_second'] = array();
      $sidebar_second_hidden = TRUE;
    }
  }

  // If the current item is NOT in indholdsmenu, clean the sidebar_first array.
  // Dont show sidebar on nodes if they are not in menu.
  if ($node) {
    $menu_trail = menu_get_active_trail();
    $active = end($menu_trail);
    if ($active['menu_name'] !== 'menu-indholdsmenu') {
      //$variables['page']['sidebar_first'] = array();
      //$sidebar_first_hidden = TRUE;
    }
  }

  // Get all the nodes selvbetjeningslinks and give them to the template.
  if (($node && $links = field_get_items('node', $node, 'field_os2web_base_field_selfserv')) ||
      ($term && $links = field_get_items('taxonomy_term', $term, 'field_os2web_base_field_selfserv'))) {
    $variables['page']['os2web_selfservicelinks'] = _svendborg_theme_get_selfservicelinks($links);
  }

  // Get all related links to this node.
  // 1. Get all unique related links from the node.
  $related_links = array();
  if (($node && $links = field_get_items('node', $node, 'field_os2web_base_field_related')) ||
      ($term && $links = field_get_items('taxonomy_term', $term, 'field_os2web_base_field_related'))) {
    foreach ($links as $link) {
      $link_node = node_load($link['nid']);
      if ($link_node) {
        $related_links[$link['nid']] = array(
          'nid' => $link['nid'],
          'title' => $link_node->title,
          'class' => 'int-link',
        );
      }
    }
  }
  // 2. Get all related links related to the KLE number on the node. Only get
  // these if the checkbox "Skjul relaterede links" isn't checked.
  if (($node &&
        (!isset($node->field_os2web_base_field_hidlinks['und'][0]['value']) ||
        $node->field_os2web_base_field_hidlinks['und'][0]['value'] == '0') &&
        $kle_items = field_get_items('node', $node, 'field_os2web_base_field_kle_ref')) ||
      ($term &&
        (!isset($term->field_os2web_base_field_hidlinks['und'][0]['value']) ||
        $term->field_os2web_base_field_hidlinks['und'][0]['value'] == '0') &&
        $kle_items = field_get_items('taxonomy_term', $term, 'field_os2web_base_field_kle_ref'))) {

    foreach ($kle_items as $kle) {
      // Get all nodes which have the same KLE number as this node.
      $query = new EntityFieldQuery();
      $result = $query->entityCondition('entity_type', 'node')
        ->propertyCondition('status', 1)
        ->fieldCondition('field_os2web_base_field_kle_ref', 'tid', $kle['tid'])
        ->propertyOrderBy('title', 'ASC')
        ->execute();
      if (isset($result['node'])) {
        foreach ($result['node'] as $link) {
          // Be sure to skip links which already is in list, or links to current
          // node.
          if (isset($related_links[$link->nid]) || ($node && $node->nid == $link->nid)) {
            continue;
          }
          $link_node = node_load($link->nid);
          if ($link_node) {
            $related_links[$link->nid] = array(
              'nid' => $link->nid,
              'title' => $link_node->title,
              'class' => 'kle-link',
            );
          }

        }
      }
    }
  }
  // External related links
  if ($node && $ext_links = field_get_items('node', $node, 'field_os2web_base_field_ext_link')) {
    foreach ($ext_links as $link) {
      $related_links[] = array(
        'url' => $link['url'],
        'title' => $link['title'],
        'class' => 'ext-link',
      );
    }
  }

  if (!empty($related_links)) {
    // Provide the related links to the templates.
    $variables['page']['related_links'] = $related_links;
  }

  // When a node's menu link is deaktivated and has no siblings, menu_block is
  // empty, and then sidebar_first are hidden. We want to force the
  // sidebar_first to still be shown.
  $active_trail = menu_get_active_trail();
  $current_trail = end($active_trail);

  if (isset($current_trail['hidden']) && $current_trail['hidden'] && empty($variables['page']['sidebar_first'])) {
    $variables['page']['sidebar_first'] = array(
      '#theme_wrappers' => array('region'),
      '#region' => 'sidebar_first',
      'dummy_content' => array(
        '#markup' => ' ',
      ),
    );
  }

  // Hack to force the sidebar_second to be rendered if we have anything to put
  // in it.
  if (!$sidebar_second_hidden && empty($variables['page']['sidebar_second']) && (!empty($variables['page']['related_links']) || !empty($variables['page']['os2web_selfservicelinks']))) {
    $variables['page']['sidebar_second'] = array(
      '#theme_wrappers' => array('region'),
      '#region' => 'sidebar_second',
      'dummy_content' => array(
        '#markup' => ' ',
      ),
    );
  }

  // On taxonomy pages, add a news list in second sidebar.
  if ($term) {
    $view = views_get_view('os2web_news_lists');
    $view->set_display('panel_pane_2');
    $view->set_arguments(array('all', 'Branding', $term->name));
    $view->set_items_per_page(3);
    $view->pre_execute();
    $view->execute();
    if (!empty($view->result)) {
      if (empty($variables['page']['sidebar_second'])) {
        $variables['page']['sidebar_second'] = array(
          '#theme_wrappers' => array('region'),
          '#region' => 'sidebar_second',
        );
      }
      $variables['page']['sidebar_second']['os2web_news_lists'] = array('#markup' => $view->render());
    }
    if($term_is_top) {
      $variables['page']['sidebar_first'] = array();
    }
  }

  // Spotbox handling. Find all spotboxes for this node, and add them to
  // content_bottom.
  if (($node && $spotboxes = field_get_items('node', $node, 'field_os2web_base_field_spotbox')) ||
      ($term && !$term_is_top && $spotboxes = field_get_items('taxonomy_term', $term, 'field_os2web_base_field_spotbox'))) {

    $variables['page']['content_bottom'] = array(
      'os2web_spotbox' => array(
        '#markup' => drupal_render(_svendborg_theme_get_spotboxes($spotboxes)),
      ),
      '#theme_wrappers' => array('region'),
      '#region' => 'content_bottom',
    );
  }

  // Add out fonts from Google Fonts API.
  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => 'http://fonts.googleapis.com/css?family=Titillium+Web:400,700|Open+Sans:400,700',
      'rel' => 'stylesheet',
      'type' => 'text/css',
    ),
  ), 'google_font_svendborg_theme');

  if ($term && strtolower($term->name) === "nyheder") {
    $variables['theme_hook_suggestions'][] = 'taxonomy_term__' . $term->tid;
  }
}

/**
 * Implements template_preprocess_taxonomy_term().
 */
function svendborg_theme_preprocess_taxonomy_term(&$variables) {

  $term = taxonomy_term_load($variables['tid']);

  // Get wether this is a top term, and provide a variable for the templates.
  $term_is_top = _svendborg_theme_term_is_top($term->tid);
  $variables['term_is_top'] = $term_is_top;

  // Provide the spotboxes to Nyheder page or top terms. These pages does not
  // use the right sidebar so we need them in taxonomy-term.tpl
  if (isset($term->tid) && (strtolower($term->name) === 'nyheder' || $term_is_top)) {
    $spotboxes = field_get_items('taxonomy_term', $term, 'field_os2web_base_field_spotbox');
    if (strtolower($term->name) === 'nyheder') {
      $variables['os2web_spotboxes'] = _svendborg_theme_get_spotboxes($spotboxes, 'col-xs-6 col-sm-6 col-md-6 col-lg-6');
    }
    else {
      $variables['os2web_spotboxes'] = _svendborg_theme_get_spotboxes($spotboxes, 'col-xs-6 col-sm-4 col-md-3 col-lg-3');
    }
  }
}

/**
 * Implements THEME_preprocess_html().
 */
function svendborg_theme_preprocess_html(&$variables) {
  // Add conditional stylesheets for IE.
  drupal_add_css(path_to_theme() . '/css/ie.css', array(
    'group' => CSS_THEME,
    'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE),
    'preprocess' => FALSE,
    'weight' => 115,
  ));

  if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
    // Add wether the term is top to the classes array.
    $term_is_top = _svendborg_theme_term_is_top(arg(2));

    if ($term_is_top) {
      $variables['classes_array'][] = 'term-is-top';
    }
    else {
      $variables['classes_array'][] = 'term-is-not-top';
    }
  }
}
/**
 * Implements hook_preprocess_node().
 */
function svendborg_theme_preprocess_node(&$vars) {

  // Add css class "node--NODETYPE--VIEWMODE" to nodes.
  $vars['classes_array'][] = 'node--' . $vars['type'] . '--' . $vars['view_mode'];

  // Make "node--NODETYPE--VIEWMODE.tpl.php" templates available for nodes.
  $vars['theme_hook_suggestions'][] = 'node__' . $vars['type'] . '__' . $vars['view_mode'];
}
/**
 * Implements theme_breadcrumb().
 *
 * Output breadcrumb as an unorderd list with unique and first/last classes.
 */
function svendborg_theme_breadcrumb($variables) {
  $breadcrumbs = $variables['breadcrumb'];

  // After disabling the Crumbs module, some taxonomies where dublicated in the
  // active trail, and then have dubs in breadcrumb.
  //
  // EIDT: WEIRD brødkrumme hack. Problem have maybe resolved itself.
  // if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
  //   array_pop($breadcrumbs);
  // }

  if (!empty($breadcrumbs)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $crumbs = '<ul class="breadcrumb">';

    foreach ($breadcrumbs as $breadcrumb) {
      $classes = array();
      if ($breadcrumb == reset($breadcrumbs)) {
        $classes[] = 'first';
      }
      if ($breadcrumb == end($breadcrumbs)) {
        $classes[] = 'last';
      }
      if (is_array($breadcrumb)) {
        if (isset($breadcrumb['class'])) {
          $classes = array_merge($classes, $breadcrumb['class']);
        }
        if (isset($breadcrumb['data'])) {
          $breadcrumb = $breadcrumb['data'];
        }
      }
      $crumbs .= '<li class="' . implode(' ', $classes) . '"><i></i>'  . $breadcrumb . '</li>';
    }
    $crumbs .= '</ul>';
    return $crumbs;
  }
}

/**
 * Overrides theme_menu_link().
 *
 * Overrides Bootstrap version. Enables to show active trails childrens.
 */
function svendborg_theme_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';
  if ($element['#below']) {
    // Prevent dropdown functions from being added to management menu so it
    // does not affect the navbar module.
    if (($element['#original_link']['menu_name'] == 'management') && (module_exists('navbar'))) {
      $sub_menu = drupal_render($element['#below']);
    }
    elseif ($element['#original_link']['in_active_trail']) {
      $sub_menu = drupal_render($element['#below']);
    }
    else {
      $element['#attributes']['class'][] = 'has-children';
    }
  }
  // On primary navigation menu, class 'active' is not set on active menu item.
  // @see https://drupal.org/node/1896674
  if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page())) && (empty($element['#localized_options']['language']))) {
    $element['#attributes']['class'][] = 'active';
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Theme function to output tablinks for classic Quicktabs style tabs.
 *
 * @ingroup themeable
 */
function svendborg_theme_qt_quicktabs_tabset($vars) {
  $variables = array(
    'attributes' => array(
      'class' => 'quicktabs-tabs quicktabs-style-' . $vars['tabset']['#options']['style'],
    ),
    'items' => array(),
  );
  foreach (element_children($vars['tabset']['tablinks']) as $key) {
    $item = array();
    if (is_array($vars['tabset']['tablinks'][$key])) {
      $tab = $vars['tabset']['tablinks'][$key];

      $class = "";
      if ($key == (count($vars['tabset']['tablinks']) - 1)) {
        $class = "last";
      }
      if ($key == $vars['tabset']['#options']['active']) {
        $item['class'] = array('active','tab-' . $key, $class);
      }
      else {
        $item['class'] = array('tab-' . $key, $class);
      }
      $item['data'] = "<div class = 'bubble' ><span>" . drupal_render($tab) . "</span></div>";
      $variables['items'][] = $item;
    }
  }
  return theme('item_list', $variables);
}

/**
 * Helper function to get a rendeable array of spotboxes.
 *
 * @param array $spotboxes
 *   Array of spotboxe nodes with nids.
 *
 * @return array
 *   The renderable array.
 */
function _svendborg_theme_get_spotboxes($spotboxes, $classes = 'col-xs-6 col-sm-4 col-md-3 col-lg-3') {
  $spotbox_nids = array();
  foreach ($spotboxes as $spotbox) {
    $spotbox_nids[$spotbox['nid']] = $spotbox['nid'];
  }
  $spotbox_array = os2web_spotbox_render_spotboxes($spotbox_nids, NULL, NULL, NULL, 'svendborg_spotbox');

  foreach ($spotbox_array['node'] as &$spotbox) {
    if (is_array($spotbox)) {
      $spotbox['#prefix'] = '<div class="' . $classes . '">';
      $spotbox['#suffix'] = '</div>';
    }
  }
  return $spotbox_array;
}

/**
 * Helper function to retrive the correct array to display selfservicelinks.
 *
 * @param array $links
 *   Associated array of links with indexes 'nid'.
 *
 * @return array
 *   Array of links with URL and Title.
 */
function _svendborg_theme_get_selfservicelinks($links) {
  $selfservicelinks = array();
  foreach ($links as $link) {
    $selfservicelink = node_load($link['nid']);
    if ($selfservicelink) {
      $link_fields = field_get_items('node', $selfservicelink, 'field_spot_link');
      if (!empty($link_fields)) {
        $link_field = array_shift($link_fields);
        $selfservicelinks[$link['nid']] = array(
          'url' => $link_field['url'],
          'title' => $link_field['title'],
        );
      }
    }
  }
  return $selfservicelinks;
}

/**
 * Helper function to return wether a term is a top term.
 *
 * @param int $term_tid
 *   The term tid.
 *
 * @return bool
 *   If this term is top.
 */
function _svendborg_theme_term_is_top($term_tid) {
  $parent = &drupal_static(__FUNCTION__ . $term_tid);
  if (empty($parent)) {
    $parent = db_query("SELECT parent FROM {taxonomy_term_hierarchy} WHERE tid = :tid", array(':tid' => $term_tid))->fetchField();
  }

  return $parent == 0;
}
