(function ($) {
Drupal.behaviors.lang_dropdown = {
  attach: function (context, settings) {
    var settings = settings || Drupal.settings;

    if (settings.lang_dropdown) {
      var flags, msddSettings;
      for (key in settings.lang_dropdown) {
      	$('select#lang-dropdown-select-' + key).once('lang-dropdown', function() {
	      	var select = $(this);
	        msddSettings = settings.lang_dropdown[key].jsWidget;
	        flags = msddSettings.languageicons;
	        if (flags) {
	          $.each(flags, function(index, value) {
	            if (msddSettings.widget == "msdropdown") {
	            	$('option[value="' + index + '"]', select).attr('data-image', value);
	            }
	            else if (msddSettings.widget == "ddslick" && Boolean(msddSettings.showSelectedHTML)) {
	            	$('option[value="' + index + '"]', select).attr('data-imagesrc', value);
	            }
	          });
	        }
	
	        if (msddSettings.widget == "msdropdown") {
	          try {
	          	select.msDropDown({
	              visibleRows: msddSettings.visibleRows,
	              roundedCorner: Boolean(msddSettings.roundedCorner),
	              animStyle: msddSettings.animStyle,
	              event: msddSettings.event,
	              mainCSS: msddSettings.mainCSS
	            });
	          }
	          catch (e) {
	            if (console) { console.log(e); }
	          }
	        }
	        else if (msddSettings.widget == "chosen") {
	        	select.chosen({
	            disable_search: msddSettings.disable_search,
	            no_results_text: msddSettings.no_results_text
	          });
	        }
	        else if (msddSettings.widget == "ddslick") {
	          $.data(document.body, 'ddslick'+key+'flag', 0);
	          select.ddslick({
	            width: msddSettings.width,
	            height: (msddSettings.height == 0) ? null : msddSettings.height,
	            showSelectedHTML: Boolean(msddSettings.showSelectedHTML),
	            imagePosition: msddSettings.imagePosition,
	            onSelected: function(data) {
	              var i = $.data(document.body, 'ddslick'+key+'flag');
	              if (i) {
	                $.data(document.body, 'ddslick'+key+'flag', 0);
	                var lang = data.selectedData.value;
	                var href = $('#lang-dropdown-select-'+key).parents('form').find('input[name="' + lang + '"]').val();
	                window.location.href = href;
	              }
	              $.data(document.body, 'ddslick'+key+'flag', 1);
	            }
	          });
	        }
      	})
      }
    }

    $('select.lang-dropdown-select-element').change(function() {
      var lang = this.options[this.selectedIndex].value;
      var href = $(this).parents('form').find('input[name="' + lang + '"]').val();
      window.location.href = href;
    });

    $('form.lang-dropdown-form').after('<div style="clear:both;"></div>');
  }
};
})(jQuery);
;
(function($) {
  Drupal.behaviors.adsk_subnav = {
    attach : function(context, settings) {
      $("div.caas_article_context > div.more").toggle(
          function() {
            $(this).children("span").text(
                Drupal.settings.caas_article_context.seeLess);
            var articleContext = $(this).siblings(
                ".caas_article_context_contain");
            articleContext.children(".complete").show();
            articleContext.children(".teaser").hide();
          },
          function() {
            $(this).children("span").text(
                Drupal.settings.caas_article_context.seeMore);
            var articleContext = $(this).siblings(
                ".caas_article_context_contain");
            articleContext.children(".complete").hide();
            articleContext.children(".teaser").show();
          });

      // Click to enlarge images on Caas-Pages..
      $(".caas-content-result img")
          .filter(function() {
            return $(this).parent("a").length === 0;
          })
          .each(
              function() {
                var origImg = $(this);
                // Create new offscreen image to test
                var theImage = new Image();
                theImage.src = origImg.attr("src");
                var imageWidth = origImg.width();
                // Get accurate measurements from that.
                $(theImage)
                    .load(
                        function() {
                          var imageRealWidth = this.width;
                          if (imageRealWidth > imageWidth) {
                            var onOpenCallback = 'function(){var cboxParent=$("img.cboxPhoto").closest("#colorbox"); cboxParent.addClass( "imageEnlarge" );'
                                + ' cboxParent.prevAll("#cboxOverlay:first").addClass("imageEnlarge"); }';
                            var anchor = $('<a/>')
                                .attr(
                                    {
                                      'class' : 'click-image-enlarge',
                                      'onclick' : '(function($){$.colorbox({href:"'
                                          + origImg.attr("src")
                                          + '",width:"auto",height:"auto",photo:true,closeButton:true, onComplete:'
                                          + onOpenCallback
                                          + ' })})(jQuery);return false;'
                                    });
                            origImg.wrap(anchor);
                          }
                        });
              });
    }
  };
})(jQuery);
;
