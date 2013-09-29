<?php
/**
 * @file
 * Include template function files.
 *
 * Template functions are grouped into files loosely based on hooks, in the
 * hopes that functions addresssing similar concerns are easy to track. For
 * instance, template functions dealing with fields are likely grouped into
 * field.inc. The reason this isn't broken down further is because there isn't
 * yet an incentive to break functions out into even more files.
 */

include 'inc/block.inc';
include 'inc/breadcrumb.inc';
include 'inc/field.inc';
include 'inc/form.inc';
include 'inc/html.inc';
include 'inc/link.inc';
include 'inc/menu.inc';
include 'inc/page.inc';
include 'inc/pager.inc';

/**
 * Creates a layout class based on the availability of regions in the given array.
 *  This function is useful if you need to adjust your layout CSS based on the
 *  presence or absence of certain regions.  Works well with pages, Panels
 *  and Display Suite layouts.
 *
 * @param array $regions
 *   An array of region machine names to check if they are empty or not.
 *
 * @return string
 *  A string in the form of l--0-1... with integers representing each region in
 *   the $regions argument.  Empty regions appear as 0. Populated regions appear
 *   as 1.
 */

function center_create_region_collection_classes($regions) {
  $class = 'l-';
  // Construct a class based on if regions are empty or not.
  foreach ($regions as $key => $region) {
    $class .= empty($region) ? '-0' : '-1';
  }
  return $class;
}

