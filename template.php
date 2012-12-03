<?php

/**
 * Implements hook_preprocess_html().
 */
function center_preprocess_html(&$variables) {
  $meta_charset = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'charset' => 'utf-8',
    ),
  );
  drupal_add_html_head($meta_charset, 'meta_charset');

  $meta_x_ua_compatible = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'http-equiv' => 'x-ua-compatible',
      'content' => 'ie=edge, chrome=1',
    ),
  );
  drupal_add_html_head($meta_x_ua_compatible, 'meta_x_ua_compatible');

  $meta_mobile_optimized = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'MobileOptimized',
      'content' => 'width',
    ),
  );
  drupal_add_html_head($meta_mobile_optimized, 'meta_mobile_optimized');

  $meta_handheld_friendly = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'HandheldFriendly',
      'content' => 'true',
    ),
  );
  drupal_add_html_head($meta_handheld_friendly, 'meta_handheld_friendly');

  $meta_viewport = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width',
    ),
  );
  drupal_add_html_head($meta_handheld_friendly, 'meta_handheld_friendly');

  $meta_cleartype = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'http-equiv' => 'cleartype',
      'content' => 'on',
    ),
  );
  drupal_add_html_head($meta_cleartype, 'meta_cleartype');
}

/**
 * Custom implementation of theme_form.
 *
 * Each form element is wrapped in a DIV container having the following CSS
 * classes:
 * - form-item: Generic for all form elements.
 * - form-type-#type: The internal element #type.
 * - form-item-#name: The internal form element #name (usually derived from the
 *   $form structure and set via form_builder()).
 * - form-disabled: Only set if the form element is #disabled.
 *
 * In addition to the element itself, the DIV contains a label for the element
 * based on the optional #title_display property, and an optional #description.
 *
 * The optional #title_display property can have these values:
 * - before: The label is output before the element. This is the default.
 *   The label includes the #title and the required marker, if #required.
 * - after: The label is output after the element. For example, this is used
 *   for radio and checkbox #type elements as set in system_element_info().
 *   If the #title is empty but the field is #required, the label will
 *   contain only the required marker.
 * - invisible: Labels are critical for screen readers to enable them to
 *   properly navigate through forms but can be visually distracting. This
 *   property hides the label for everyone except screen readers.
 * - attribute: Set the title attribute on the element to create a tooltip
 *   but output no label element. This is supported only for checkboxes
 *   and radios in form_pre_render_conditional_form_element(). It is used
 *   where a visual label is not needed, such as a table of checkboxes where
 *   the row and column provide the context. The tooltip will include the
 *   title and required marker.
 *
 * If the #title property is not set, then the label and any required marker
 * will not be output, regardless of the #title_display or #required values.
 * This can be useful in cases such as the password_confirm element, which
 * creates children elements that have their own labels and required markers,
 * but the parent element should have neither. Use this carefully because a
 * field without an associated label can cause accessibility challenges.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #title_display, #description, #id, #required,
 *     #children, #type, #name.
 *
 * @ingroup themeable
 */
function center_form_element($variables) {
  $element = &$variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="form-item-description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}

/**
 * Custom Implementation of theme_form_item_label.
 *
 * Form element labels include the #title and a #required marker. The label is
 * associated with the element itself by the element #id. Labels may appear
 * before or after elements, depending on theme_form_element() and
 * #title_display.
 *
 * This function will not be called for elements with no labels, depending on
 * #title_display. For elements that have an empty #title and are not required,
 * this function will output no label (''). For required elements that have an
 * empty #title, this will output the required marker alone within the label.
 * The label will use the #id to associate the marker with the field that is
 * required. That is especially important for screenreader users to know
 * which field is required.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #required, #title, #id, #value, #description.
 *
 * @ingroup themeable
 */
function center_form_element_label($variables) {
  $element = $variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
    return '';
  }

  // If the element is required, a required marker is appended to the label.
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element['#title']);

  $attributes = array(
    'class' => array('form-item-label'),
  );
  // Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after') {
    $attributes['class'][] = 'option';
  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'][] = 'element-invisible';
  }

  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }

  // The leading whitespace helps visually separate fields from inline labels.
  return ' <label' . drupal_attributes($attributes) . '>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
}


/**
 * Implements hook_preprocess_page().
 */
function center_preprocess_page(&$vars) {
  $vars['title_attributes_array']['class'][] = 'page-title';
}

/**
 * Implements hook_preprocess_node().
 */
function center_preprocess_node(&$vars) {
}

/**
 * Implements hook_html_head_alter().
 */
function center_html_head_alter(&$head_elements) {
  // Remove system content type meta tag.
  unset($head_elements['system_meta_content_type']);
}

/**
 * Implements hook_js_alter().
 */
function center_js_alter(&$javascript) {
  // Collect the scripts we want in to remain in the header scope.
  $header_scripts = array(
    'sites/all/libraries/modernizr/modernizr.min.js',
  );

  // Change the default scope of all other scripts to footer.
  // We assume if the script is scoped to header it was done so by default.
  // foreach ($javascript as $key => &$script) {
  //   if ($script['scope'] == 'header' && !in_array($script['data'], $header_scripts)) {
  //     $script['scope'] = 'footer';
  //   }
  // }
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
 * Custom implementation of theme_menu_link().
 *
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
 * LINKS
 */

/**
 * Overrides theme_links().
 *
 * This adds classes to the lists to make them more in line with menus.
 */
function center_links($variables) {
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  global $language_url;
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {

        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = array($key);
      $class[] = 'nav-item';
      $link['attributes']['class'][] = 'nav-link';

      // Add first, last and active classes to lists of links to help themers.
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
           && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {

        // Some links are actually not links, but we wrap these in <span> for
        // adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

/**
 * FIELDS
 */

/**
 * Overrides theme_field().
 *
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
 * Overrides theme_ds_field_minimal().
 *
 * Still allow the normal drupal field classes to apply.
 */
function center_field__minimal($variables) {
  $output = '';

  $config = $variables['ds-config'];

  // Add a simple wrapper.
  $output .= '<div class="' . $variables['classes'] . '">';

  // Render the label.
  if (!$variables['label_hidden']) {
    $output .= '<div' . $variables['title_attributes'] . '>' . $variables['label'];
    if (!isset($config['lb-col'])) {
      $output .= ':&nbsp;';
    }
    $output .= '</div>';
  }

  // Render the items.
  foreach ($variables['items'] as $delta => $item) {
    $output .= drupal_render($item);
  }
  $output .= "</div>";

  return $output;
}

/**
 * Overrides theme_ds_field_minimal().
 *
 * Still allow the normal drupal field classes to apply.
 */
function center_field__raw($variables) {
  $output = '';

  // Render the items.
  foreach ($variables['items'] as $delta => $item) {
    $output .= drupal_render($item);
  }

  return $output;
}

/**
 * Custom implementation of theme_field().
 *
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
  // $output .= '<div ' . $variables['content_attributes'] . '>';
  $count = 1;
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<span ' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</span>';
    if ($count < count($variables['items'])) {
      $output .= ', ';
    }
    $count++;
  }

  // $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}

/**
 * Custom implementation of theme_field().
 *
 * Takes a url and renders it as a download link.
 * USAGE: $vars['theme_hook_suggestions'][] = 'field__custom_download';
 */
function center_field__custom_download($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<label ' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</label>';
  }

  // Render the items.
  $count = 1;
  foreach ($variables['items'] as $delta => $item) {
    $output .= l(
      t('Download'),
      $item['#markup'],
      array(
        'attributes' => array(
          'class' => array('download-link'),
        ),
      )
    );
  }

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}

/**
 * Custom implementation of theme_field().
 *
 * Wraps the field in an h3 and nukes the label an outer wrapper.
 * USAGE: $vars['theme_hook_suggestions'][] = 'field__custom_h2';
 */
function center_field__custom_h2($variables) {
  $output = '';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<h2 ' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</h2>';
  }
  return $output;
}

/**
 * Custom implementation of theme_field().
 *
 * Wraps the field in an h3 and nukes the label an outer wrapper.
 * USAGE: $vars['theme_hook_suggestions'][] = 'field__custom_h3';
 */
function center_field__custom_h3($variables) {
  $output = '';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<h3 ' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</h3>';
  }
  return $output;
}

/**
 * NODES
 */

/**
 * BLOCKS
 */

/**
 * BREADCRUMBS
 */

/**
 * Custom implementation of theme_breadcrumb().
 *
 * Add some comments here.
 */
function center_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {

    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' <span class="breadcrumb-separator">/</span> ', $breadcrumb) . '</div>';
    return $output;
  }
}
