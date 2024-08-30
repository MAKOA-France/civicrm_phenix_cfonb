(function($, Drupal, drupalSettings) {

    Drupal.behaviors.cfonb = {
      attach: function(context, settings) {

        $(document).ready(function() {
            // This function is toogle of last document
            
            var layouts = $('.section-civicrm-event article .layout.layout--onecol');
  
            // Check if there are at least 2 such elements
            if (layouts.length > 1) {
              // Apply styles to the second one
              layouts.eq(1).css('margin-top', '60px');
            }
                      

            $('body').on('click', '.line-container-plus .btn-see-other-doc, .line-container-moins .btn-dismiss-other-doc', function() {console.log('test')
              if ($(this).hasClass('btn-see-other-doc')) {
                console.log('clicke fired KALY')
                $('.section-others-documents').show();
                $('.line-container-plus').hide();
              } else {
                console.log('ELSE CONDITION')
                  $('.section-others-documents').hide();
                  $('.line-container-plus').show();
              }
            });
        });

      }
    }
})(jQuery, Drupal, drupalSettings);    