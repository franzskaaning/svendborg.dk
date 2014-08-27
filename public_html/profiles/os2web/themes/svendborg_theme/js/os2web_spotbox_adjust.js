
(function($) {

  /**
   * Script to move spotboxes around, where there are room. Eg. if any roomm
   * available under right sidebar, put some there.
   *
   * Also adds some cleafixes.
   */
  $(window).load(function(){

    var $region_sidebar = $('.region-sidebar-second'),
        $spotboxes = $('.node-os2web-spotbox-box');

    // Be sure to only do it when not on mobile width.
    if ($region_sidebar.length &&
        $(window).width() > 768 &&
        ($('body').hasClass('page-node') || ($('body').hasClass('page-taxonomy-term') && $('body').hasClass('term-is-not-top')))) {
      var $region_content = $('.region-content'),
          sidebar_height = $region_sidebar.outerHeight(),
          content_height = $region_content.outerHeight();

      // Only do magic if the sidebar is smaller than content.
      if(sidebar_height < content_height) {
        var diff = content_height - sidebar_height;
        $spotboxes.each(function(i) {
          var $spotbox = $(this),
              $wrap = $spotbox.parent(),
              height = $spotbox.outerHeight();

          // If there are room for another spotbox, add it.
          if (height < diff) {
            $region_sidebar.append($spotbox);
            $wrap.remove();
            // Update the space left.
            diff = diff - height;
          }
          else {
            // No more room, break out of loop.
            return false;
          }
        });
      }
    }
    // Clear any floats each nth item. Spotboxes dont have same heights, so a
    // .clearfix fixes the floats.
    // This has to still be on window.load, because we maybe move some spotboxes
    // around.
    $('.region-content-bottom > div:not(.clearfix)').each(function(i) {
      // -md and -lg has four columns
      if ((i + 1)%4 === 0) {
        $(this).after('<div class="clearfix visible-md visible-lg"></div>');
      }
      // -sm devices has three columns.
      if ((i + 1)%3 === 0) {
        $(this).after('<div class="clearfix visible-sm"></div>');
      }
      // -xs has two columns
      if ((i + 1)%2 === 0) {
        $(this).after('<div class="clearfix visible-xs"></div>');
      }
    });
  });

})(jQuery);
