/* Svendborg theme script
*/
( function ($) {
  $(document).ready(function(){

    // Navbar scroll
    $(window).bind('scroll', function() {
        var navHeight = $( window ).height();
        if ($(window).scrollTop() > 41 && $(window).width() > 768 ) {
          $('.header_svendborg header').addClass('navbar-fixed-top');
          $('.header_fixed_inner').addClass('container');
          $('.header_svendborg header').removeClass('container');
          $('.main-container').css('padding-top','114px');
          $('#fixed-navbar').addClass('row');

          // Frontpage top (navbar) search forum.
          $(".front .region-navigation.navbar-fixed-top .search_box").addClass('col-md-3 col-sm-4');
          $(".front .region-navigation.navbar-fixed-top .search_box").removeClass('col-md-1 col-sm-1');

          $(".front .region-navigation.navbar-fixed-top .nav_main_menu").addClass('col-md-9 col-sm-8');
          $(".front .region-navigation.navbar-fixed-top .nav_main_menu").removeClass('col-md-11 col-sm-11');
        }
        else {
          $('.header_svendborg header').removeClass('navbar-fixed-top');
          $('.header_fixed_inner').removeClass('container');
          $('.header_svendborg header').addClass('container');
          $('.main-container').css('padding-top','initial');
          $('#fixed-navbar').removeClass('row');
          // Frontpage top (navbar) search forum.
          $(".front .region-navigation.container .search_box").addClass('col-md-1 col-sm-1');
          $(".front .region-navigation.container .search_box").removeClass('col-md-3 col-sm-4');

          $(".front .region-navigation.container .nav_main_menu").addClass('col-md-11 col-sm-11');
          $(".front .region-navigation.container .nav_main_menu").removeClass('col-md-9 col-sm-3');
        }
    });

    // Nyheder page filter
    $('.node-os2web-base-news').each(function(){
      var $this = $(this);

      $this.parent().attr('data-filter',$this.attr('date-filter'));
      $this.parent().addClass($this.attr('date-filter'));
    });


    var button = 'filter-all';
    var button_class = "btn-primary";
    var button_normal = "btn-blacknblue";

    // Initial masonry
    var $container = $("#nyheder-content-isotoper .view-content");
    if ($container.length) {

      $container.imagesLoaded(function(){
        $container.masonry({
          itemSelector: '.switch-elements',
          columnWidth: '.switch-elements',
        });
        // filter elements
        $container.isotope({
          itemSelector: '.switch-elements',
        });
        $(".filter-link").click(function() {
          button = $(this).attr('id');
          var filterValue = $( this ).attr('data-filter');
          filterValue = '.'+filterValue;
          $container.isotope({ filter: filterValue });
          check_button();
        });
        $(".filter-link-all").click(function() {
    
          $container.isotope({ filter: '.all' });
          button = $(this).attr('id');
          check_button();
        });

        function check_button(){
          $('.filter-link').removeClass(button_class);
          $(".filter-link-all").removeClass(button_class);
          $('.filter-link').addClass(button_normal);
          $(".filter-link-all").addClass(button_normal);
          $('#'+button).addClass(button_class);
          $('#'+button).removeClass(button_normal);
        }
        check_button();

      });
    }

    // borger.dk articles
      $("div.mArticle").hide();
      $(".microArticle a.gplus").click(function() {
        var article = $(this).parent().find('h2');
        var myid = article.attr('id');
        var style = $('div.' + myid).css('display');
        var path = $(this).css("background-image");
        if (style == 'none') {
          $("div." + myid).show("500");
          $(this).addClass('gminus');
          $(this).removeClass('gplus');
        }
        else {
          $("div." + myid).hide("500");
          $(this).addClass('gplus');
          $(this).removeClass('gminus');
        }
        return false;
      });
      $(".gplus_all").click(function() {
        $("div.mArticle").show();
        $(".microArticle a.gplus").addClass('gminus');
        $(".microArticle a.gplus").removeClass('gplus');
        return false;
      });

      $(".gminus_all").click(function() {
        $(".microArticle a.gminus").addClass('gplus');
        $(".microArticle a.gminus").removeClass('gminus');
        $("div.mArticle").hide();
        return false;
      });

      // front nav header search_form button
      $(".front .region-navigation.container #search-block-form button").click(function(){
        $( ".main-container .front-search-box input" ).focus();

        return false;
      });

    $('#feedback-submit').addClass('btn-primary');

  });

Drupal.behaviors.feedbackForm = {
  attach: function (context) {
    $('#block-feedback-form', context).once('feedback', function () {
      var $block = $(this);
      $block.find('span.feedback-link')
        .prepend('<span id="feedback-form-toggle">[ + ]</span> ')
        .css('cursor', 'pointer')
        .toggle(function () {
            Drupal.feedbackFormToggle($block, true);
          },
          function() {
            Drupal.feedbackFormToggle($block, false);
          }
        );
      $block.find('form').hide();
      $block.show();
    });
  }
};

/**
 * Re-collapse the feedback form after every successful form submission.
 */
Drupal.behaviors.feedbackFormSubmit = {
  attach: function (context) {
    var $context = $(context);
    if (!$context.is('#feedback-status-message')) {
      return;
    }
    // Collapse the form.
    $('#block-feedback-form .feedback-link').click();
    // Blend out and remove status message.
    window.setTimeout(function () {
      $context.fadeOut('slow', function () {
        $context.remove();
      });
    }, 3000);
  }
};

/**
 * Collapse or uncollapse the feedback form block.
 */
Drupal.feedbackFormToggle = function ($block, enable) {
  if (enable) {
    $block.animate({width:"329px"});
    $block.css('z-index','960');
    $block.find('form').css('display','block');
    $('#feedback-form-toggle', $block).html('[ + ]');
    var cittaslow = $('#block-cittaslow-block');
    if (cittaslow.width() > 51) {
      Drupal.cittaslowToggle(cittaslow, false);
    }
  }
  else {
    $block.animate({width:"29px"});
    $block.css('z-index','900');
    $('#feedback-form-toggle', $block).html('[ &minus; ]');
  }
};

Drupal.behaviors.cittaslow= {
  attach: function (context) {
    $('#block-cittaslow-block', context).once(function () {
      var $block = $(this);
      $block.find('span.cittaslow-link').toggle(function () {
          if ($block.width() < 300) {
            Drupal.cittaslowToggle($block, true);
          }
          else {
            Drupal.cittaslowToggle($block, false);
          }

          },
          function() {
            if ($block.width() < 300) {
              Drupal.cittaslowToggle($block, true);
            }
            else {
              Drupal.cittaslowToggle($block, false);
            }
          }
        );
      $block.show();
    });
  }
};

  Drupal.cittaslowToggle = function ($block, enable) {

  if (enable) {
    $block.animate({width:"351px"});

  }
  else {
    $block.animate({width:"51px"});
  }
};

})( jQuery );


/**
 * Re-collapse the feedback form after every successful form submission.
 */
Drupal.behaviors.feedbackFormSubmit = {
  attach: function (context) {}
};
