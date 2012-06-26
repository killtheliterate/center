<?php

/**
 * @file
 * Display Suite teaser layout configuration.
 */

function ds_teaser_layout() {
  return array(
    'label' => t('Teaser Layout'),
    'regions' => array(
      'header' => t('Header'),
      'figure' => t('Figure'),
      'aside' => t('Aside'),
      'section' => t('Section'),
      'footer' => t('Footer'),
    ),
    // Uncomment if you want to include a CSS file for this layout (teaser_layout.css)
    // 'css' => TRUE,
    // Uncomment if this is a template for a node form.
    // 'form' => TRUE,
  );
}
