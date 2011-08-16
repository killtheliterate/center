<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=edge, chrome=1" />
<meta name = "viewport" content = "initial-scale = 1.0, width = device-width" />
<title><?php print $head_title; ?></title>
<?php 

if (theme_get_setting('toggle_favicon')) { 
  $favicon = theme_get_setting('favicon');
} else {
  $favicon = "/sites/all/themes/nucleus/images/favicon.ico";
} ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php print $favicon; ?>" />
<?php print $styles; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
  </div>
  <?php
    print $page_top;
    print $page;
    print $scripts;
    print $page_bottom;
  ?>
</body>
</html>