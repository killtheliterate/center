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
 * Menus
 */

/**
 * Overrides theme_menu_tree() for the main menu.
 */

function center_menu_tree__main_menu($variables) {
  return '<ul class="nav navbar">' . $variables['tree'] . '</ul>';
}

/**
 * Fields
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
 * Theme function for comma separated field items
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
 * Implements hook_preprocess_field()
 */

function center_preprocess_field(&$vars) {
  /* Set shortcut variables. Hooray for less typing! */
  $field = $vars['element']['#field_name'];
  $bundle = $vars['element']['#bundle'];
  $mode = $vars['element']['#view_mode'];
  $classes = &$vars['classes_array'];
  $title_classes = &$vars['title_attributes_array']['class'];
  $content_classes = &$vars['content_attributes_array']['class'];
  $item_classes = array();

  /* Global field styles */
  $classes[] = 'field-wrapper';
  $title_classes[] = 'field-label';
  $content_classes[] = 'field-items';
  $item_classes[] = 'field-item';

  /* Uncomment the line below to see variables you can use to target a field */
  #print '<strong>Field:</strong> ' . $field . ' | <strong>Bundle:</strong> ' . $bundle  . ' | <strong>Mode:</strong> ' . $mode .'<br/>';

  /* Example: Using an alternative theme function */
  // if($field == 'field_tags') {
  //   $vars['theme_hook_suggestions'][] = 'field__custom_separated';
  // }

  // Apply odd or even classes along with our custom classes to each item */
  foreach ($vars['items'] as $delta => $item) {
    $item_classes[] = $delta % 2 ? 'odd' : 'even';
    $vars['item_attributes_array'][$delta]['class'] = $item_classes;
  }
}

/**
 * Blocks
 */

/**
 * Implements hook_preprocess_block()
 */

function center_preprocess_block(&$vars) {
  /* Set shortcut variables. Hooray for less typing! */
  $block_id = $vars['block']->module . '-' . $vars['block']->delta;
  $classes = &$vars['classes_array'];
  $title_classes = &$vars['title_attributes_array']['class'];
  $content_classes = &$vars['content_attributes_array']['class'];

  /* Add global classes to all blocks */
  $title_classes[] = 'block-title';
  $content_classes[] = 'block-content';

  /* Uncomment the line below to see variables you can use to target a field */
  #print $block_id . '<br/>';

  // /* Add classes based on the block delta. */
  // switch ($block_id) {
  //   /* System Navigation block */
  //   case 'system-navigation':
  //     $classes[] = 'block-rounded';
  //     $title_classes[] = 'block-fancy-title';
  //     $content_classes[] = 'block-fancy-content';
  //     break;
  // }
}