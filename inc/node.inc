<?php

/**
 * Implements hook_preprocess_node().
 */
function center_preprocess_node(&$vars) {
  // Set shortcut variables. Hooray for less typing!
  $bundle = $vars['type'];
  $mode = $vars['view_mode'];

  // Create a development print variable.
  $vars['dev_deets'] = theme_get_setting('dev_mode') ?
    '<!-- Bundle: ' . $bundle . ' | View Mode: ' . $mode . ' -->' : '';
}
