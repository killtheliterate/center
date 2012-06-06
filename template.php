<?php

/**
 * Implements hook_js_alter()
 */

function center_js_alter(&$javascript) {
  // Collect the scripts we want in to remain in the header scope.
  $header_scripts = array(
    'sites/all/libraries/modernizr/modernizr.min.js',
  );

  // Change the default scope of all other scripts to footer.
  // We assume if the script is scoped to header it was done so by default.
  foreach ($javascript as $key => &$script) {
    if ($script['scope'] == 'header' && !in_array($script['data'], $header_scripts)) {
      $script['scope'] = 'footer';
    }
  }
}

/**
 * MENUS
 */

/**
 * Overrides theme_menu_tree().
 */

function center_menu_tree($variables) {
  return '<ul class="nav">' . $variables['tree'] . '</ul>';
}

/**
 * Custom implementation of theme_menu_link()
 * This adds a span for including icons before a menu link.
 */

function center_menu_link__icon(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  /* Prevent the <span> tag from being escaped */
  $element['#localized_options']['html'] = TRUE;

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  $icon = '<span' . drupal_attributes($element['#icon_attributes']) . '></span>';
  $output = l($icon . $element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Overrides theme_menu_local_tasks().
 */

function center_menu_local_tasks(&$variables) {
  $output = '';

  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
    $variables['primary']['#prefix'] .= '<ul class="nav nav-tabs">';
    $variables['primary']['#suffix'] = '</ul>';
    $output .= drupal_render($variables['primary']);
  }
  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
    $variables['secondary']['#prefix'] .= '<ul class="nav nav-pills">';
    $variables['secondary']['#suffix'] = '</ul>';
    $output .= drupal_render($variables['secondary']);
  }

  return $output;
}

/**
 * FIELDS
 */

/**
 * Overrides theme_field()
 * Remove the hard coded classes so we can add them in preprocess functions.
 */

function center_field($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div ' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  $output .= '<div ' . $variables['content_attributes'] . '>';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<div ' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}

/**
 * Custom implementation of theme_field()
 * Turns multivalued fields into a comma separated list.
 * USAGE: $vars['theme_hook_suggestions'][] = 'field__custom_separated';
 */

function center_field__custom_separated($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<label ' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</label>';
  }

  // Render the items.
  //$output .= '<div ' . $variables['content_attributes'] . '>';
  $count = 1;
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<span ' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</span>';
    if ($count < count($variables['items'])) { $output .= ', '; }
    $count++;
  }
  //$output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}

/**
 * NODES
 */

/**
 * BLOCKS
 */
