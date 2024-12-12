(function($, Drupal, drupalSettings) {

    Drupal.behaviors.cfonbContext = {
      attach: function(context, settings) {

        $(document).ready(function() {


 

           


          if ($('.section-civicrm-event').length) {
            let table_event_participant = $('.section-civicrm-event .vbo-view-form table.vbo-table')
            if (!table_event_participant.length) {
              $('.layout__region.layout__region--content .views-element-container.contextual-region.block-views').hide();
            }
          }
        

             var $list = jQuery('.block-views-block-civievents-base-sur-le-contact-block-3 .item-list ul');
          if ($list) {

            // Obtenir les <li> et les trier
            var $items = $list.children('li').sort(function(a, b) {
              // Extraire le texte dans <li> > div > span > a
              var textA = jQuery(a).find('div > span > a').text().toLowerCase();
              var textB = jQuery(b).find('div > span > a').text().toLowerCase();
              
              // Comparer alphabétiquement
              return textA.localeCompare(textB);
            });
            
            // Réattacher les <li> triés dans la liste <ul>
            $list.append($items);
          }


          var $list = jQuery('.block-views-block-civievents-base-sur-le-contact-block-5 .item-list ul li');
          if ($list) {

           var listItems = jQuery('.block-views-block-civievents-base-sur-le-contact-block-5 .item-list ul li').get(); // Get all the <li> elements

            // Sort the <li> elements alphabetically based on the text of the <a> tag inside the <li>
            listItems.sort(function(a, b) {
              var textA = jQuery(a).find('a').text().toUpperCase(); // Get the anchor text
              var textB = jQuery(b).find('a').text().toUpperCase(); // Get the anchor text

              return textA.localeCompare(textB); // Compare the text values
            });

            // Re-append the sorted <li> elements back to the <ul>
            jQuery('.block-views-block-civievents-base-sur-le-contact-block-5 .item-list ul').append(listItems);
          }


          if ($('.page-qui-sommes-nous-nos-adherents').length) {
            let urlimg = jQuery('.page-qui-sommes-nous-nos-adherents #block-b-zf-content .content  img:first-of-type').attr('src');
            $('.page-qui-sommes-nous-nos-adherents section#main').attr('data-bg', urlimg)
            $('.page-qui-sommes-nous-nos-adherents section#main').addClass('has-before').attr('data-bg', urlimg);
            let box = $('.page-qui-sommes-nous-nos-adherents .has-before')
            const bgImage = box.attr('data-bgimage');
            $('.page-qui-sommes-nous-nos-adherents section#main').attr('style', '--bgimage: url("'+urlimg+'")');
            if (bgImage) {
              console.log(bgimage, ' hasl')
            }
          }

          if ($('.page-qui-sommes-nous-notre-histoire').length) {
            let urlimg = jQuery('.section-qui-sommes-nous #block-b-zf-content .content  img:first-of-type').attr('src');
            $('.section-qui-sommes-nous section#main').attr('data-bg', urlimg)
            $('.section-qui-sommes-nous section#main').addClass('has-before').attr('data-bg', urlimg);
            let box = $('.section-qui-sommes-nous .has-before')
            const bgImage = box.attr('data-bgimage');
            $('.section-qui-sommes-nous section#main').attr('style', '--bgimage: url("'+urlimg+'")');
            if (bgImage) {
              console.log(bgimage, ' hasl')
            }

          }


          // Créer un objet pour regrouper les éléments par année
          var groupedByYear = {};

          // Parcourir chaque .item-list
          jQuery('.page-rencontres-ateliers .printParticipants .item-list').each(function () {
            // Récupérer l'année depuis <h3>
            var year = jQuery(this).find('h3 time').text().trim();

            // Si l'année n'existe pas encore dans l'objet, on la crée
            if (!groupedByYear[year]) {
              groupedByYear[year] = [];
            }

            // Ajouter l'élément courant au groupe correspondant
            groupedByYear[year].push(jQuery(this));
          });


          console.log(groupedByYear, ' l')
      
          if ($('.page-rencontres-ateliers').length) {

            // Conteneur principal où se trouvent les .item-list
            var container = jQuery('.printParticipants');
            
            // Nettoyer le conteneur principal
            container.empty();
            console.log(groupedByYear, ' aol');
            
            // Trier les années par ordre décroissant
            var sortedYears = Object.keys(groupedByYear).sort(function(a, b) {
                return b - a;  // Tri décroissant des années
            });
            
            // Ajouter chaque groupe au conteneur principal, année par année
            jQuery.each(sortedYears, function (index, year) {
                // Créer un conteneur pour l'année
                var yearContainer = jQuery('<div class="year-group"></div>').append('<h2>' + year + '</h2>');
                let itemContainer = jQuery('<div class="all-itemss"></div>');
                
                // Ajouter les éléments de cette année au conteneur
                groupedByYear[year].forEach(function (item) {
                    itemContainer.append(item);
                });
        
                // Ajouter le conteneur des items au conteneur de l'année
                yearContainer.append(itemContainer);
        
                // Ajouter le conteneur d'année au conteneur principal
                container.append(yearContainer);
            });
        }
 



          if ( jQuery('.bloc-head-meetings-in-theme .meeting-head').length) {
            if (!jQuery('.views-element-container.block-views').length) {
              jQuery('.bloc-head-meetings-in-theme .meeting-head').insertAfter('.bloc-head-meetings-in-theme .block-layout-builder');
            }else {
              jQuery('.bloc-head-meetings-in-theme .meeting-head').insertBefore('.layout--twocol-section.layout--twocol-section--50-50');

            }
          }

          once('cfonbConctext', '.page-c-taxonomy-ss .menu-c-nav-item, .page-type-node-cus  .menu-c-nav-item, .section-contact .menu-c-nav-item ', context).forEach((elem) => {
            $(elem).on('mouseover', function(el, id) {
              let svg = $(this).find('svg')
              svg.css('background-color', '#971762');
            })
            $(elem).on('mouseleave', function(el, id) {
              let svg = $(this).find('svg')
              svg.css('background-color', '#d3278c');
            })
          })

          once('cfonbContextz', '.btn-see-rapport', context).forEach((elementss) => {
            $(elementss).on('click', function() {
              let ur = $(elementss).find('a').attr('href');
              location.href = ur; 
            })
          });

          once('cfonbContext', '.c-each-com', context).forEach((element) => {
            $(element).on('mouseout', function () {
              $(element).find('.img-illust-doc').removeClass('img-illust-doc-added')
              
            })
            $(element).on('mouseover', function () {
              console.log(element, $(element.closest('.img-illust-doc')))
             $(element).find('.img-illust-doc').addClass('img-illust-doc-added')
            });
          });

          if ($('#views_slideshow_cycle_div_block_hero-block_1_1').length)
          {
            $('#block-b-zf-textedebienvenuhome h1').addClass('custom-margin'); 
          }
          let img = jQuery('.page-c-taxonomy-ss').attr('data-bg-img');
          if (img) {

          let style = `
              background-image: url("${img}") !important;
              background-size: cover !important;
              background-position: center !important;
              background-repeat: no-repeat !important;
          `;

          // Apply the style attribute to the target element
          jQuery('.page-c-taxonomy-ss .grid-container.hero-taxonomy').attr('style', style);

          }

          jQuery('.section-accueil-extranet #block-views-block-civievents-base-sur-le-contact-block-1 header > h2').addClass('group-h2');
          jQuery('.section-accueil-extranet #block-views-block-civievents-base-sur-le-contact-block-1 header > h2').attr('id','commissions-block-adherent');
          
          if ($('.views-element-container.medium-6.block-views.block-views-block-civievents-base-sur-le-contact-block-2').length <2) {
            jQuery('.views-element-container.medium-6.block-views-block-civievents-base-sur-le-contact-block-2').insertBefore('.section-accueil-extranet  .block-views-block-civievents-base-sur-le-contact-block-1');
          }
          
          jQuery('.group-h2').insertBefore('.section-accueil-extranet  .block-views-block-civievents-base-sur-le-contact-block-1');

          jQuery('.section-civicrm-group .layout.layout--twocol-section.layout--twocol-section--50-50 .layout__region--first').insertAfter('.layout__region--second');


          $("#backtotop").wrap('<div class="wrapper-c-btn"></div>');

            // This function is toogle of last document

            if (window.matchMedia("(min-width: 992px)").matches) {
              jQuery('.medium-9 .grid-x.align-middle .cell.medium-1').insertBefore('.medium-9 .grid-x.align-middle .cell.medium-11');
            }


          var $wrapper = $('<div class="maclasse"></div>');
          $('.block-views-block-actualites-block-1 .views-row').wrapAll($wrapper);

            if (!$('.btn-my-account').length) {
              $('<button class="btn-my-account" data-toggle="dropdown" data-target="#submenu-my-profil"></button>').insertBefore('[block="block-b-zf-account-menu"]');
            }
            
            

            $(window).on('scroll', function() {

              if (window.matchMedia("(min-width: 992px)").matches) {
                 
                var scrollTop = $(window).scrollTop();
                if ($('.detail-group-title').length || $('.page-communication').length) {

                    // if ($('.group-presentation-description').offset()) {                    
                      var elementOffset = $('.detail-group-title').length ? $('.detail-group-title').offset().top : $('.grid-container.na-pages').offset().top ;
                      // Check if the element touches the top of the screen
                      if (scrollTop <= elementOffset) {
                        $('#block-b-zf-navigationmeetingandgroupblock').css('position', 'static');
                      jQuery('.dynamic-content').css('top', '-21px');
                      jQuery('.dynamic-content').css('width', '500px');
                    } else {
                      $('#block-b-zf-navigationmeetingandgroupblock').css('position', 'fixed');
                      jQuery('.dynamic-content').css('top', '0');
                      jQuery('.dynamic-content').css('width', '500px');
                      
                    }
                  // }
                }

                if ($('.top-bar#main-menu').length) {
                  let menuoffset = $('.top-bar#main-menu').offset().top;
                  if (scrollTop <= menuoffset) {
                    $('.grid-container.na-pages').addClass('new-before-style');
                  }else {
                    $('.grid-container.na-pages').removeClass('new-before-style');
                  }
                }

                  if ($('#block-b-zf-navigationmeetingandgroupblock').length) {

                    let footerOffset = $('#footer').offset().top - 50;
                    let scrollTop = $(window).scrollTop();
                    let testOffset = $('#block-b-zf-navigationmeetingandgroupblock').offset().top;
                    let testHeight = $('#block-b-zf-navigationmeetingandgroupblock').outerHeight();

                    // Si l'élément #test atteint le #footer
                    if (scrollTop + testHeight >= footerOffset) {
                        $('#block-b-zf-navigationmeetingandgroupblock').css({
                            'position': 'static',
                            'top': '0'
                        });
                      }
                }
              }
            });



            
            var layouts = $('.section-civicrm-event article .layout.layout--onecol');
  
            // Check if there are at least 2 such elements
            if (layouts.length > 1) {
              // Apply styles to the second one
              layouts.eq(1).css('margin-top', '60px');
            }
                      

            once('cfonbConctext', '.line-container-plus .btn-see-other-doc, .line-container-moins .btn-dismiss-other-doc ', context).forEach((elemme) => {
              $(elemme).on('click', function(){
                if ($(this).hasClass('btn-see-other-doc')) {
                    console.log('clicke fired KALY')
                    jQuery(this).parents('.medium-12.page-detail-groupoes').next('.section-others-documents').toggle();
                    jQuery(this).parents('.medium-12.page-detail-groupoes').find('.line-container-plus').hide();
                    // $('.section-others-documents.page-detail-groupoes').show();
                    // $('.line-container-plus').hide();
                  } else {
                    console.log('ELSE CONDITION')
                    jQuery('.custom-my-account').hide();
                    jQuery('.medium-12.page-detail-groupoes').find('.line-container-plus').show();
                  }
              })
            })
       

            $('body').on('click', '.line-container-plus .btn-see-other-doc, .line-container-moins .btn-dismiss-other-doc', function() {console.log('test')
              // if ($(this).hasClass('btn-see-other-doc')) {
              //   console.log('clicke fired KALY')
              //   $('.section-others-documents').show();
              //   $('.line-container-plus').hide();
              // } else {
              //   console.log('ELSE CONDITION')
              //     $('.section-others-documents').hide();
              //     $('.line-container-plus').show();
              // }
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


if (window.matchMedia("(min-width: 992px)").matches) {
  // Cacher le contenu lorsque la souris quitte la barre latérale
  sidebar.addEventListener('mouseleave', () => {
      const contents = textBlock.querySelectorAll('.dynamic-content');
      contents.forEach(content => {
          content.style.display = 'none';
      });
  });
}




        });

      }
    }
})(jQuery, Drupal, drupalSettings);    


(function($) {
  Drupal.behaviors.civicrm_phenix_cfonb = {
      attach: function(context, settings) {
        $(window).on('load', function() {



          
          once('cfonbConctext', 'nav#block-b-zf-account-menu ul.menu li ', context).forEach((elem) => {
            $(elem).on('click', function(el, id) {
              // let curHre = $(this).closest('a').attr('href');
              let curHre = $(elem).find('a');
              location.href = curHre.attr('href');
            })
          });

          $('#block-b-zf-account-menu').on('click', function() {
            // if (!$('.online_people').length ) {
            //   console.log('anonymous')
            //   $('[block="block-b-zf-account-menu"]').toggle();
            //   if ($('[block="block-b-zf-account-menu"]').is(':visible')) {
            //     // console.log('displayed')
            //     $('[block="block-b-zf-account-menu"]').hide();
            //   }else {
            //     // console.log('hidden')
            //     $('[block="block-b-zf-account-menu"]').show();
                
            //   }
            // }
          })

          if ($('#block-b-zf-account-menu').length) {
            console.log('HOXK HONK', $('#block-b-zf-account-menu').length)
            once('civicrm_phenix_cfonb', '#block-b-zf-account-menu', context).forEach(function (element) {
              $(element).on('click', function() {
                if (!$('.online_people').length) {
                  $('[block="block-b-zf-account-menu"]').toggle();
                }
                if ($('.online_people').length) {

                  if ($(window).width() < 992) {
                    
                    console.log('here we')
                    $('[block="block-b-zf-account-menu"]').toggle();
                  }
                }
              })
              element.addEventListener('click', function () {
                // console.log('cliky')
              });
            });
          }

          let visible = jQuery('[block="block-b-zf-account-menu"]').is(':visible');

          if (visible) {
            // jQuery('[block="block-b-zf-account-menu"]').hide();
          }



          //Trier les element dans la rubrique instrument de paiement 
          // Select all elements with the target class
          var gridElements = jQuery('.page-espace-documentaire-instruments-de-paiement .grid-x.grid-margin-x.grid-margin-y');

          if (gridElements) {
            // Select all elements with the target class
            // var gridElements = jQuery('.grid-x.grid-margin-x.grid-margin-y');

            // Sort elements based on the text of the <h2> inside each element
            gridElements.sort(function (a, b) {
              var textA = jQuery(a).find('h2').first().text().trim().toLowerCase();
              var textB = jQuery(b).find('h2').first().text().trim().toLowerCase();
              return textA.localeCompare(textB);
            });

            // Append the sorted elements back to the parent container
            gridElements.appendTo(gridElements.parent());
          }




          $('.float-right .fa-search').on('click', function(event) {
            // event.preventDefault();
            let searcs = $('#site-search').val();
            console.log('azil', searcs);
            if (searcs) {
              location.href = "/recherche?recherche=" + searcs;
            }
          });

          
          if ($('.btn-my-account').length) {
            console.log('HOXK HONK', $('#block-b-zf-account-menu').length)
            once('civicrm_phenix_cfonb', '#block-b-zf-account-menu', context).forEach(function (element) {
              element.addEventListener('click', function () {
                console.log('cliky')
                  $('[block="block-b-zf-account-menu"]').toggle();
              });
            });
          }

 
          if ($('.btn-my-account').length) {
            once('civicrm_phenix_cfonb', '#block-b-zf-account-menu', context).forEach(function (element) {
              element.addEventListener('click', function () {
                if (!$('.role--anonymous').length) {
                  $('[block="block-b-zf-account-menu"]').toggle();
                }
              });
            });
          }

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

          if (window.matchMedia("(min-width: 992px)").matches) {
            jQuery('.custom-element').insertAfter('.btn-my-account');
          }



          if ($('.btn-my-account').length) {
            once('civicrm_phenix_cfonb', '#block-b-zf-account-menu', context).forEach(function (element) {
              $('[block="block-b-zf-account-menu"]').hide();
              element.addEventListener('click', function () {
                console.log('cliky')
                if (!$('.role--anonymous').length) {
                  $('[block="block-b-zf-account-menu"]').toggle();
                }
              });

              // Ajouter un gestionnaire d'événements pour cacher le menu si on clique ailleurs sur la page

            });
          }
            // Ajouter un gestionnaire d'événements pour cacher le menu si on clique ailleurs sur la page
            let elem = $('#block-b-zf-account-menu');
            var $menu = $('[block="block-b-zf-account-menu"]');
            $(document).click(function(event) {
              if ($menu && elem) {
                if (!$(event.target).closest($menu).length && !$(event.target).closest(elem).length) {
                  if (!$('.online_people').length) {
                    $menu.hide(); // Cacher le menu
                  }
                }
              }
            });

      }) 
      }
  }
})(jQuery)