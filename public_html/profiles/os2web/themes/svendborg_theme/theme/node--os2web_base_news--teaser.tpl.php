<?php
  $portalkategori = field_get_items('node',$node,'field_os2web_base_field_struct');
  //$term = taxonomy_term_load($portalkategori[0]['tid']);

?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes . " all"; ?> clearfix"<?php print $attributes; ?> date-filter="<?php print $portalkategori[0]['tid']; ?>">
  <?php if(!$page) : ?>

    <div class=" ">
    <div class="front-s-news-item front-s-news-item-">
      <?php if(isset($content['field_os2web_base_field_lead_img'])) : ?>
            <div class="front-s-news-item-img">
              <?php
                $img = field_get_items('node',$node,'field_os2web_base_field_lead_img');
                $image = $img[0];
                $style = 'large'; 
                $public_filename = image_style_url($style, $image["uri"]);
                $path = drupal_get_path_alias('node/'.$node->nid);
                echo '<a href="' . $path . '" title="'.$node->title.'">';
                print $html = '<img title = "'.$image["title"].'" src="'.$public_filename.'""/></a>';
              ?>
              <?php //print render($content['field_os2web_base_field_lead_img']); ?>
            </div>
      <?php endif; ?>
            <div class="front-s-news-item-text clearfix">
                <div class="col-md-2 col-sm-2 col-xs-2">
                     <div class="news-text-date">
                       <span class="news-date-day"><?php print date('j', $created); ?></span>
                       <span class="news-date-month"><?php print date('M', $created); ?></span>
                     </div>
                 </div>

                <div class="col-md-10 col-sm-10 col-xs-10">
                      <div><a class="news-title" href="<?php global $base_url; print$base_url. $node_url; ?>"><?php print $node->title; ?></a></div>
                      <div>
                          <p><?php print render($content['field_os2web_base_field_summary']); ?></p>
                      </div>
                </div>

            </div>
            </div>
    </div>
  <?php endif; ?>

    <?php
      // Hide comments, tags, and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      hide($content['field_tags']);
      hide($content['field_os2web_base_field_image']);
      hide($content['field_os2web_base_field_lead_img']);
    ?>

  <?php if (!empty($content['field_tags']) || !empty($content['links'])): ?>

    <?php hide($content['field_tags']); ?>
    <?php hide($content['links']); ?>
  <?php endif; ?>
  <?php hide($content['comments']); ?>
</article>
