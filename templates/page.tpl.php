<div id="page">

  <div id="header">
    <div class="container">
      <h1 id="logo">
        <?php if ($logo): ?>
          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
            <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
          </a>
        <?php else: ?>
          <a href="<?php print $base_path; ?>"><?php print $site_name; ?></a>
        <?php endif; ?>
      </h1>
      <?php if ($page['utility']) { ?>
        <div id="utility">
          <div class="container">
            <?php print render($page['utility']); ?>
          </div>
        </div>
      <? } // end utility ?>
      <?php if ($page['header']) { ?>
          <?php print render($page['header']); ?>
      <? } // end header ?>
    </div>
  </div>
  
  <?php if ($page['navigation'] || $main_menu) { ?>
    <div id="navigation" class="clearfix">
      <div class="container">
        <?php if ($main_menu): ?>
          <div id="main-menu" class="navigation">
            <?php print theme('links__system_main_menu', array(
              'links' => $main_menu,
              'attributes' => array(
                'id' => 'main-menu-links',
                'class' => array('links', 'clearfix'),
              ),
              'heading' => array(
                'text' => t('Main menu'),
                'level' => 'h2',
                'class' => array('element-invisible'),
              ),
            )); ?>
          </div> <!-- /#main-menu -->
        <?php endif; ?>
        <?php print render($page['navigation']); ?>
      </div>
    </div>
  <? } // end navigation & main_menu ?>

  <div id="main">
    <div class="container">
      
      <?php if ($messages) { ?>
        <div id="messages">
            <?php print $messages; ?>
        </div>
      <? } // end messages ?>
      
      <div id="main-content">
        
        <?php if (render($tabs)) { ?>
          <div id="tabs">
            <?php print render($tabs); ?>
          </div>
        <? } // end tabs ?>
        
        <div id="content">
        
          <?php if ($page['highlighted']) { ?>
            <div id="highlighted">
              <div class="container">
                <?php print render($page['highlighted']); ?>
              </div>
            </div>
          <? } // end highlighted ?>
          
          <?php if ($page['featured']) { ?>
            <div id="featured">
              <div class="container">
                <?php print render($page['featured']); ?>
              </div>
            </div>
          <? } // end featured ?>
        
          <?php if (!$is_front && strlen($title) > 0) { ?>
            <h1><?php print $title; ?></h1> 
          <? } ?>
          
          <?php if ($page['help']) { ?>
            <div id="help">
              <?php print render($page['help']); ?>
            </div>
          <? } // end tabs ?>
          
          <?php print render($page['content']); ?>
          
        </div>
      
        <?php if ($page['sidebar_first']) { ?>
          <div id="sidebar-first" class="aside">
            <?php print render($page['sidebar_first']); ?>
          </div>
        <? } // end sidebar_first ?>
        
      </div>
      
      <?php if ($page['sidebar_second']) { ?>
        <div id="sidebar-second" class="aside">
          <?php print render($page['sidebar_second']); ?>
        </div>
      <? } // end sidebar_second ?>
      
    </div>
  </div>
  
  <div id="footer">
    <div class="container">
      <?php print render($page['footer']); ?>
    </div>
  </div>
  
  <?php if ($page['admin_footer']) { ?>
    <div id="admin-footer">
      <div class="container">
        <?php print render($page['admin_footer']); ?>
      </div>
    </div>    
  <? } // end admin_footer ?>
  
</div>
