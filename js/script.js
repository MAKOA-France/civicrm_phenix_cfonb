(function($, Drupal, drupalSettings) {

    Drupal.behaviors.cfonb = {
      attach: function(context, settings) {

        $(document).ready(function() {
            // This function is toogle of last document
            console.log('qmlsdkjmdqlkf')
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