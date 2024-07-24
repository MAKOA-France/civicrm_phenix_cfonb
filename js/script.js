(function($, Drupal, drupalSettings) {

    Drupal.behaviors.phenix = {
      attach: function(context, settings) {

        $(document).ready(function() {

          jQuery('.views-field.views-field-user-bulk-form [type="checkbox"]').on('change', function() {
            if (jQuery('.views-field.views-field-user-bulk-form [type="checkbox"]:checked').length) {
              let allId = [];
              jQuery('.views-field.views-field-user-bulk-form [type="checkbox"]:checked').each(function(id, el) {
                let encryptedId = jQuery(el).val();
                // Decode the Base64 encoded string
                let decodedString = atob(encryptedId);
                
                // Parse the JSON string
                let jsonArray = JSON.parse(decodedString)[1];
                allId.push(jsonArray);
              })
              let ids = JSON.stringify(allId);
              $('.link-to-send-mail').attr('data-all-id', ids)
            }else {
              $('.link-to-send-mail').attr('data-all-id', '')
            }
          })

          $('.link-to-send-mail').once('check').on('click', function() {
            if ($(this).attr('data-all-id')) {
              let ids = $(this).attr('data-all-id');console.log(ids, 'kkk', $(this).attr('data-all-id'))
               $.ajax({
                url: '/pesonnes/morethanthreemonth',
                data: {id: ids},
                success: (successResult, val, ee) => {
                  let allmail = successResult.mail;

                  let content = "Bonjour, Nous avons remarqué que vous ne vous êtes pas connecté(e) à votre compte depuis plus de trois mois. Nous espérons que tout va bien pour vous."
"                                    Nous tenions à vous rappeler que votre compte adhérent vous donne accès à de nombreuses ressources et avantages exclusifs. Pour continuer à bénéficier de tous ces services, nous vous invitons à vous reconnecter dès que possible."    
"              Cliquez sur le bouton ci-dessous pour vous connecter à votre compte :";

                  let mailtoLink = 'mailto:' + allmail + '?subject=INACTIVATION DE COMPTE DEPUIS PLUS DE 3 MOIS&body=' + content;
                  location.href = mailtoLink
                  // $('.link-to-send-mail').
                  console.log('valeur : ', successResult)
                },
                error: function(error) {
                  console.log(error, 'ERROR')
                }
              }); 
            }
          })

            $('#printerParticipants').on('click', function () {
              printContent() 
          });
          
          function printContent() {

            // Get all elements with the class "hidden"
var elementsToRemove = document.querySelectorAll('.hidden');

  // Remove each element
  elementsToRemove.forEach(function(element) {
      element.remove();
  });
            let logo = `
            <img class="img-logoo hahhhhhh" src="https://cfonbmaj.dev.makoa.net/sites/cfonbmaj.dev.makoa.net/files/logo-cfonb.jpg" alt="CFONB">
                <a href="/" title="CFONB" rel="home" class="site-logo">
                </a>
              `;

            var contentToPrint = $('#printerParticipants').attr('data-content-to-print'); // Replace 'elementId' with the ID of the element you want to print.

            
                        // Convert the HTML string to a jQuery object
            var $content = $(contentToPrint);

            // Remove all elements with class "hidden"
            $content.find('.hidden').remove();
            $content.find('#vbo-action-form-wrapper').remove();
            $content.find('.form-actions.js-form-wrapper.form-wrapper').remove();

            // Update the data-content-to-print attribute with the modified HTML
            $('#printerParticipants').attr('data-content-to-print', $content.html());
            contentToPrint = $content.html();

            var printWindow = window.open('', '_blank');
            if (printWindow) {
              // printWindow.document.write('<html><head><title>Print</title><style>table {     border-collapse: collapse;    width: 100%;    margin-bottom: 1rem;    border-radius: 3px; } tbody th, tbody td {padding: 1rem 0.625rem 0.625rem;}  thead th, thead td, tfoot th, tfoot td {    padding: 0.5rem 0.625rem 0.625rem;} .menu--account h2, a {     color: #af1f7b;text-decoration: none;transition: all .5s ease-in; } tbody tr:nth-child(even){    background-color: #f1f1f1;    border-bottom: 0;} tbody th, tbody td {padding :0.5rem 0.625rem 0.625rem;}thead th, thead td, tfoot th, tfoot td {    padding: 0.5rem 0.625rem 0.625rem;    text-align: left;}.to-print thead,.to-print  tbody,.to-print  tfoot {    border: 1px solid #f1f1f1;    background-color: #fefefe;  } .to-print .form-actions.js-form-wrapper.form-wrapper, .hidden {display:none} #view-register-date-table-column {padding-right: 30px;}</style></head><body><div class="to-print">');
              let htmlHead = '<html><head><title>Print</title><style>table {    border: 1px solid #93838329}th, td{border: 1px solid gray;}.to-print thead,.to-print  tbody,.to-print  tfoot {    border: 1px solid #f1f1f1;    background-color: #fefefe;  } .to-print .form-actions.js-form-wrapper.form-wrapper, .hidden {display:none} #view-register-date-table-column {padding-right: 30px;}</style></head><body><div class="to-print">';
              let htmlFeet = '</div></body></html>';
              contentToPrint =  htmlHead + logo + contentToPrint + htmlFeet ;
              printWindow.document.write(contentToPrint);
              // printWindow.document.write('</div></body></html>');
              printWindow.document.close();
              includeCSSInline(customCSS, printWindow);
              //adding custom css
              var customCSS = `
              thead {
              background: #f8f8f8;
              color: #0a0a0a;
            }
            tbody tr:nth-child(even) {
              border-bottom: 0 !important;
              background-color: #f1f1f1 !important;
            }
            
            `;
            function includeCSSInline(cssCode, printWindow) {
              var style = printWindow.document.createElement('style');
              style.type = 'text/css';
              style.appendChild(printWindow.document.createTextNode(cssCode));
              printWindow.document.head.appendChild(style);
            }
            printWindow.print();
            

            // Attach an event listener to the 'afterprint' event
            window.addEventListener('afterprint', function() {
              // Call the performAjaxRequest function after the print operation is complete
              alert(' window')
            });
            // Attach an event listener to the 'afterprint' event
            printWindow.addEventListener('afterprint', function() {
              // Call the performAjaxRequest function after the print operation is complete
              alert(' print window')
            });

            $.ajax({
              url: '/meeting/savepdf',
              type: "POST",
              data: {contentToPrint: contentToPrint, idEvent : window.location.href.split('/feuille-de-presence/')[1]},
              success: (successResult, val, ee) => {
                
                console.log('valeur : ', successResult)
              },
              error: function(error) {
                console.log(error, 'ERROR')
              }
            });
          }

          }
          
        });



        

      }
    }
})(jQuery, Drupal, drupalSettings);    