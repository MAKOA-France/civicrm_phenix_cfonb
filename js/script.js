(function($, Drupal, drupalSettings) {

    Drupal.behaviors.cfonbContext = {
      attach: function(context, settings) {

        $(document).ready(function() {
            // This function is toogle of last document

          var $wrapper = $('<div class="maclasse"></div>');
          $('.block-views-block-actualites-block-1 .views-row').wrapAll($wrapper);

            if (!$('.btn-my-account').length) {
              $('<button class="btn-my-account" data-toggle="dropdown" data-target="#submenu-my-profil"></button>').insertBefore('[block="block-b-zf-account-menu"]');
            }
            
            

            $(window).on('scroll', function() {
              var scrollTop = $(window).scrollTop();
              if ($('.group-presentation-description').offset()) {

                var elementOffset = $('.group-presentation-description').offset().top;
                
                // Check if the element touches the top of the screen
                if (scrollTop <= elementOffset) {
                  $('#block-b-zf-navigationmeetingandgroupblock').css('position', 'static');
                  console.log('relatkiv')
                  jQuery('.dynamic-content').css('top', '23%');
                  jQuery('.dynamic-content').css('width', '23%');
                } else {
                  $('#block-b-zf-navigationmeetingandgroupblock').css('position', 'fixed');
                  jQuery('.dynamic-content').css('top', '0');
                  jQuery('.dynamic-content').css('width', '600%');
                  console.log('fixed li')
                  
                }
              }
            });



            
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

            const menuItems = document.querySelectorAll('.menu-c-nav-item');
const textBlock = document.getElementById('text-block');
const sidebar = document.querySelector('.container-c-nav');

// Fonction pour afficher le div correspondant à l'élément survolé
function showContent(targetId) {
    // Masquer tous les contenus
    const contents = textBlock.querySelectorAll('.dynamic-content');
    contents.forEach(content => {
        content.style.display = 'none';
    });

    // Afficher le contenu correspondant
    const targetContent = document.getElementById(targetId);
    if (targetContent) {
        targetContent.style.display = 'block';
    }
}

// Écouter les événements sur les éléments du menu
menuItems.forEach(item => {
    item.addEventListener('mouseover', () => {
        const targetId = item.getAttribute('data-target');
        showContent(targetId);
    });
});

// Cacher le contenu lorsque la souris quitte la barre latérale
sidebar.addEventListener('mouseleave', () => {
    const contents = textBlock.querySelectorAll('.dynamic-content');
    contents.forEach(content => {
        content.style.display = 'none';
    });
});




        });

      }
    }
})(jQuery, Drupal, drupalSettings);    


(function($) {
  Drupal.behaviors.civicrm_phenix_cfonb = {
      attach: function(context, settings) {
        console.log('firing')
        $(window).on('load', function() {

          // Create the new div container
    // var $container = $('<div class="cust-actu-container"></div>');

    // // Insert the new div before the first .views-row
    // $('#views_slideshow_cycle_div_block_hero-block_1_0 .views-row').first().before($container);

    // // Move all .views-row elements into the new div container
    // $('#views_slideshow_cycle_div_block_hero-block_1_0 .views-row').appendTo($container);
    
    

  // Close the new div after the last views-row



          if (!$('.icon-custom-user-account').length) {
            jQuery('[href="/user"]').prepend('<i class="icon-custom-user-account"></i>');
          }
          if (!$('.icon-custom-logout-accout.cus-cus').length) {
            jQuery('[data-drupal-link-system-path="user/logout"]').prepend('<i class="icon-custom-logout-accout cus-cus"></i>');
          }
          if (!$('.icon-custom-refresh.todiee').length) {
            jQuery('a[href*="/unmasquerade"]').prepend('<i class="icon-custom-refresh todiee"></i>');
          }

          jQuery('.custom-element').insertAfter('.btn-my-account');



          if ($('.btn-my-account').length) {
            once('civicrm_phenix_cfonb', '#block-b-zf-account-menu', context).forEach(function (element) {
              element.addEventListener('click', function () {
                $('[block="block-b-zf-account-menu"]').toggle();
              });
            });
          }
        }) 
      }
  }
})(jQuery)