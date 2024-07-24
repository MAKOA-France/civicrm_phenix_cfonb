<?php

namespace Drupal\phenix_cfonb_group\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Language\LanguageManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\webform\Entity\Webform;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\Core\File\FileSystemInterface;

/**
 * Defines PhenixController class.
 */
class PhenixController extends ControllerBase
{

    public function savePdf() {
        $response = new Response();
        $pdfContent = \Drupal::request()->request->get('contentToPrint');
        $idEvent = \Drupal::request()->get('idEvent');

        // Créer une instance de la classe personnalisée
        $pdf = new MyCustomPDF();

        // Ajouter du contenu HTML au PDF
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        $pdf->writeHTML($pdfContent, true, false, true, false, '');

        $currentDateTime = date('Y-m-d_H-i-s');

        // Obtenez le contenu du PDF sous forme de chaîne binaire
        $pdfContent = $pdf->Output('', 'S');

        $idEvent = str_replace("#", "", $idEvent);
        if( strpos($idEvent, '?') !== false) {
            $idEvent = explode('?', $idEvent);
            $idEvent = $idEvent[0];
        }
        // Chemin de destination pour enregistrer le fichier PDF
        $destination = 'public://documents/feuille-de-presence-' . $idEvent . '-date-' . $currentDateTime . '.pdf';

        try {
            // Enregistrez le contenu du PDF dans un fichier
            $temp_file = file_save_data($pdfContent, $destination, FileSystemInterface::EXISTS_REPLACE);
            \Drupal::logger('your_module')->info('Fichier PDF créé avec succès. URI : @uri', ['@uri' => $temp_file->getFileUri()]);
        } catch (\Exception $e) {
            \Drupal::logger('your_module')->error('Erreur lors de la création du fichier PDF : @error', ['@error' => $e->getMessage()]);
        }


        $docAlreadyExist = $this->checkIfDocumentFeuillePresenceExisteDeja ($idEvent);
        if ($docAlreadyExist) {
            $mid = $docAlreadyExist->mid;
            $existingMedia = Media::load($mid);
            
              // Update the necessary fields.
            $existingMedia->set('field_media_document', ['target_id' => $temp_file->id()]);

            // Save the updated media entity.
            $existingMedia->save();

            return $response ;
        }else {

            // Créer un média de type document
            $media = Media::create([
                'bundle' => 'document', // Remplacez par le nom de votre type de média document
                'uid' => 1, // ID de l'utilisateur qui possède le média
                'status' => 1, // 1 pour publié, 0 pour non publié
                'name' => 'Feuille de presence ' . $idEvent . '-date-' .  $currentDateTime, // Nom du média
                'field_media_document' => [
                'target_id' => $temp_file->id(), // Lien vers le fichier temporaire
                ],
            ]);

            $media->set('field_type_de_document', 30);//30 id feuille de presence

            // Sauvegarder le média
            $media->save();


            $max_delta = \Drupal::database()->query('select max(delta) as max from civicrm_event__field_documents where entity_id = '.$idEvent )->fetch();
            $max_delta = $max_delta ? $max_delta->max + 1 : 0;
            
            $already_exist = \Drupal::database()->query("select entity_id from civicrm_event__field_documents where entity_id = ".$idEvent ." and langcode = 'fr'")->fetch()->entity_id;
            if ($already_exist) {
                $query  = \Drupal::database()->query("Update civicrm_event__field_documents set field_documents_target_id = " . $media->id() . " where entity_id = " . $idEvent. " and langcode = 'fr'");
            }else {
                \Drupal::database()->query("insert into civicrm_event__field_documents (bundle, delta, entity_id, langcode, revision_id,  field_documents_target_id)
                VALUES ('reunion', " . $max_delta . ", " . $idEvent . ", 'fr', " . $idEvent . ",  " . $media->id() . ")");
            }


            $response->headers->set('Content-Disposition', 'attachment; filename="example.pdf"');

            return $response;
        }
    }

    private function checkIfDocumentFeuillePresenceExisteDeja ($idEvent) {

        // Load the existing media entity based on your criteria.
        return \Drupal::database()->query("select mid from media_field_data where name like '%Feuille de presence ". $idEvent ."-date%'")->fetch();
    }

    public function UserNotConnectedOverMonth() {
        $response =
        $allIds = \Drupal::request()->query->get('id');
        $email = '';
        if ($allIds) {
            \Drupal::service('civicrm')->initialize();
            $allIds = json_decode($allIds);
            foreach($allIds as $id) {
                $cid = $this->getCidAndEmail($id)[0]['contact_id'];

                //TODO PROVISOIRE , IL FAUT RECUPERER LE MAIL DU REFERENT A LA PLACE
                $email .= $this->getCidAndEmail($id)[0]['uf_name'] . ';';
            }
        }

        return  new JsonResponse(['mail' => $email]);
    }

    private function getCidAndEmail ($id) {
        $contact_id = NULL;
        \Drupal::service('civicrm')->initialize();
        $account = \Drupal::currentUser()->id();
        $uFMatches = $uFMatches = \Civi\Api4\UFMatch::get(FALSE)
        ->addSelect('uf_name', 'contact_id')
        ->addWhere('uf_id', '=', $id)
        ->execute()->getIterator();
        return iterator_to_array($uFMatches);
    }
}



// Créer une classe personnalisée héritant de TCPDF
class MyCustomPDF extends \TCPDF {
  // Vous pouvez ajouter des fonctionnalités personnalisées ici si nécessaire
}

