<?php

namespace Drupal\civicrm_phenix_cfonb\Plugin\Block;

use Drupal\node\Entity\Node;
use \Drupal\node\NodeInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Provides a 'Block document group' block.
 *
 * @Block(
 *  id = "document_document_group",
 *  admin_label = @Translation("Block document group"),
 *  category = @Translation("Block document group"),
 * )
 */
class DocumentBlock  extends BlockBase  {





  /**
   * {@inheritdoc}
   */
  public function build() {

    // Get the current user.
    $current_user = \Drupal::currentUser();
    $civi_service = \Drupal::service('civicrm_phenix_cfonb.custom_service');

    $request = \Drupal::request();
    $reqId = $request->attributes->get('civicrm_group')->id();

    $connection = \Drupal::database();
    $query = $connection->select('civicrm_group__field_documents_groupe', 'c')
      ->fields('c', ['field_documents_groupe_target_id'])
      ->condition('entity_id', $reqId);

    $result = $query->execute()->fetchAll();
    if ($result) {
      $doc_ids = array_column($result, 'field_documents_groupe_target_id');
      




    // $req = "select field_documents_groupe_target_id from civicrm_group__field_documents_groupe where   entity_id = 168;";


    $all_documents = [];
    $total_docs = 0;
    foreach ($doc_ids as $paragraph_id) {
      $media = \Drupal\media\Entity\Media::load($paragraph_id);
      if ($media) {
        $id_document = $media->get('field_media_document')->getValue();
        if ($id_document) {

          $title_doc = $media->label();
          $file = $media->get('field_media_document')->entity;
          $mimetype = $file->getMimeType();
          $createfileurl = $file->createFileUrl();
          $sizz = $civi_service->getFileSize($file);

          $created_at = $civi_service->convertTimesptamToDate($civi_service->getNodeFieldValue($media, 'created'));

          switch($mimetype) {
            case 'application/pdf':
              $file_type = 'pdf-3.png';
              break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
              $file_type = 'pdf-2.png';
              break;
              case 'application/msword':
              $file_type = 'pdf-2.png';
              break;
              case 'application/rtf':
                $txt_file = '.rtf';
              break;
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
              $file_type = 'pdf.png';
              break;
            case 'application/vnd.ms-excel':
              $file_type = 'pdf.png';
              break;
            case 'application/zip':
              $file_type = 'zip-file.png';
              break;
            }



          $all_documents[$title_doc][] = [
            'fileType' => $file_type,
            'fileurl' => $file->createFileUrl(),
            'size' => $sizz,
            'fileId' => $file->id(),
            'description' => $title_doc,
            'created_at' => $created_at,
            'media_id' => $media->id(), 
          ];


// dump($all_documents);

          /* $nom_document = $id_document[0]['description'];
          $id_document = $id_document[0]['target_id'];
          $file = \Drupal\file\Entity\File::load($id_document);
          if($file) {
            $total_docs++;
            $file_uri = $file->get('uri')->getValue()[0]['value'];
            $tmp_uri = $file_uri;


            $tmp_name = str_replace ('public://documents/', '', $tmp_uri);
            $nom_document = $nom_document ? $nom_document : $tmp_name;

            $file_url = file_create_url($file_uri);
            $link = \Drupal\Core\Link::fromTextAndUrl($nom_document, \Drupal\Core\Url::fromUri($file_url));
            $link_html .= '
            <img loading="lazy" src="/files/styles/vignette_24x24/public/media-icons/generic/generic.png">
            '
            . \Drupal::service('renderer')->render($link->toRenderable())->__toString(). '<br>';

          } */
        }
      }
    }

     if (!empty($all_documents)) {


    $get_first_element = reset($all_documents);
    $first_title = isset(array_keys($all_documents)[0]) ? array_keys($all_documents)[0] : '';
    $first_element_id = $get_first_element[0]['media_id'];
    $media = \Drupal\media\Entity\Media::load($first_element_id);
    $file_type = 'application/pdf';//default
    if ($media) {
      // $title = $first_title;
      // $first_element_file_id = getNodeFieldValue($media, 'field_media_document');
      // $first_file_document = File::load($first_element_file_id);
      // $first_file_extension = getNodeFieldValue($first_file_document, 'filemime');
      $file_type = $get_first_element[0]['fileType'];
    }
    $file_size_readable = $get_first_element[0]['size'];


    // // Convert the size to a human-readable format
    // $file_size_readable = round($file_size_bytes / 1024, 2);

    $date_doc = $civi_service->convertTimesptamToDate($civi_service->getNodeFieldValue($media, 'created'));

    // $html = ['#markup' => $media_extrait];
    // $html = \Drupal::service('renderer')->render($html);
    unset($all_documents[$first_title][0]);

    //Récuperation du second dernier document
    /* $secondElm = reset($all_documents)[1];
    $secondElmId = $secondElm['media_id'];
    $media = Media::load($secondElmId);
    $secondDataDoc = $civi_service->getDataSecondDocument($media);
    unset($all_documents[$first_title][1]);   *///TODO Commenté pour le moment à decommenté si on en a besoin
    //end recuperation ****----------
    
    // $allowToEdit = $civi_service->checkIfUserCanEditDoc ();
    
    $counted_doc = count(reset($all_documents));

    $file_id = $civi_service->getNodeFieldValue($media, 'field_media_document');
    $file0bj = File::load($file_id);
    $file_size_readable = $civi_service->getFileSize($file0bj);
    // $display_see_other_doc = ($counted_doc > 1); TODO 
    $display_see_other_doc = (count($all_documents) > 1 || count(reset($all_documents)));//TODO à mettre sup à 2 car les deux seront au dessus


// dump($all_documents, $display_see_other_doc);
    $module_path = \Drupal::service('module_handler')->getModule('civicrm_phenix_cfonb')->getPath();
    $image_path = $module_path . '/file/pdf-3.png';

    // $event_id = $build['#civicrm_event']->get('id')->getValue()[0]['value'];


    switch($file_type) {
      case 'application/pdf':
        $file_type = 'pdf-3.png';
        break;
      case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
        $file_type = 'pdf-2.png';
        break;
        case 'application/msword':
        $file_type = 'pdf-2.png';
        break;
        case 'application/rtf':
          $txt_file = '.rtf';
        break;
      case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
        $file_type = 'pdf.png';
        break;
      case 'application/vnd.ms-excel':
        $file_type = 'pdf.png';
        break;
      }

    
    return $build['documents'] = [
      '#theme' => 'civicrm_phenix_cfonb_custom_last_doc',
      '#cache' => ['max-age' => 0],
      '#content' => [
        'pic' => $image_path,
        'data' => $all_documents,
        'first_element' => $get_first_element,
        'first_title' => $first_title,
        'file_type' => $file_type,
        'file_size' => $file_size_readable/* $file_size */,
        'date_doc' => $date_doc,
        'first_element_id' => $first_element_id,
        'first_element_title' => $first_title,
        'display_see_other_doc' => $display_see_other_doc,
        'is_page_last_doc' => true,
        'page_detail_group' => 'page-detail-groupoes',
        'can_edit_doc' => true,//$allowToEdit,
        // 'second_document_data' => $secondDataDoc
      ]
    ];

  }
    
    
    return [
      '#theme' => 'document_group',
      '#cache' => ['max-age' => 0],
      '#content' => [
        'data' => $data,
      ],
    ];
    }
  }

  private function getAllDocs ($groupId) {
    $db = \Drupal::database();
    $custom_service = \Drupal::service('civicrm_phenix_cfonb.custom_service');
    $res = $db->query('select * from media__field_groupes where field_groupes_target_id  = ' . $groupId)->fetchAll();
    $res = array_column($res, 'entity_id');
    unset($res[0]);
    return $res;
  }

  private function getAllDocuments ($groupId) {
    $allInfoDocs = [];
    $db = \Drupal::database();
    $custom_service = \Drupal::service('civicrm_phenix_cfonb.custom_service');
    $res = $db->query('select * from media__field_groupes where field_groupes_target_id  = ' . $groupId)->fetchAll();//TODO USE ABOVE FUNCTION
    $res = $this->getAllDocs($groupId);


    $docs = \Drupal::service('entity_type.manager')->getStorage('media')->loadMultiple($res);
    $firstDoc = reset($docs);
    if ($firstDoc) {

      $allInfoDocs['first_title'] = $custom_service->getNodeFieldValue($firstDoc, 'name');
      $allInfoDocs['first_type_de_document'] = $custom_service->getTypeDocument ($firstDoc);
      $allInfoDocs['first_element_id'] = $custom_service->getNodeFieldValue($firstDoc, 'mid');
      $date_doc = $custom_service->getNodeFieldValue($firstDoc, 'created');
      $datetime = new DrupalDateTime();
      $datetime->setTimestamp($date_doc);
      
      // Format the date using the desired format.
      $formatted_date = $datetime->format('d.m.Y');
      $allInfoDocs['date_doc'] = $formatted_date;
      $fileValue = $custom_service->getNodeFieldValue($firstDoc, 'field_media_document');
      $file = File::load($fileValue);
      $fileType = $custom_service->getNodeFieldValue($file, 'filemime');
      $fileType = $fileType =='application/pdf' ? 'pdf-3.png' : 'pdf-2.png';//todo mettre switch et ajouter tous les types de fichiers
      $allInfoDocs['type_file'] = $this->getFileType($firstDoc);

      // // Get the file size in bytes   TODO GET FILE PATH
      $file_uri = $custom_service->getNodeFieldValue($file, 'uri');
      $file_path = file_create_url($file_uri);
      $file_size_bytes = filesize($file_path);
      $file_size_bytes = round($file_size_bytes / 1024, 0);
      $allInfoDocs['file_size_readable'] = $file_size_bytes;
      $date_doc = str_replace(' ', '.', $date_doc);
      $media_extrait = $custom_service->getNodeFieldValue ($firstDoc, 'field_resume');
      $allInfoDocs['resume'] = $media_extrait;
      return $allInfoDocs;
    }
  }

  private function getFile ($media) {
    $custom_service = \Drupal::service('civicrm_phenix_cfonb.custom_service');
    $fileValue = $custom_service->getNodeFieldValue($media, 'field_media_document');
    $file = File::load($fileValue);
    return $file;
  }

  private function getFormattedDate($media) {
    $custom_service = \Drupal::service('civicrm_phenix_cfonb.custom_service');
    $date_doc = $custom_service->getNodeFieldValue($media, 'created');
    $datetime = new DrupalDateTime();
    $datetime->setTimestamp($date_doc);

    // Format the date using the desired format.
    return $datetime->format('d.m.Y');
  }

  private function getFileSize ($media) {
    $custom_service = \Drupal::service('civicrm_phenix_cfonb.custom_service');
    $file = $this->getFile($media);
    $file_uri = $custom_service->getNodeFieldValue($file, 'uri');
    $file_path = file_create_url($file_uri);
    $file_size_bytes = filesize($file_path);
    $file_size_bytes = round($file_size_bytes / 1024, 0);
    return $file_size_bytes;
  }
  
  private function totalMembers ($group_id) {
    return \Civi\Api4\GroupContact::get(FALSE)
      ->addSelect('COUNT(id) AS count')
      ->addWhere('group_id', '=', $group_id)
      ->addWhere('status', '!=', 'Removed')
      ->execute()->first()['count'];
  }

  private function getGroupName($group_id) {
    return \Civi\Api4\Group::get(FALSE)
      ->addSelect('title')
      ->addSelect('frontend_title')
      ->addSelect('frontend_description')
      ->addWhere('id', '=', $group_id)
      ->execute()->first();
  }

  private function getFileType ($media) {
    $custom_service = \Drupal::service('civicrm_phenix_cfonb.custom_service');
    $fileValue = $custom_service->getNodeFieldValue($media, 'field_media_document');
    $file = File::load($fileValue);
    $fileType = $custom_service->getNodeFieldValue($file, 'filemime');
    $fileType = $fileType =='application/pdf' ? 'pdf-3.png' : 'pdf-2.png';//todo mettre switch et ajouter tous les types de fichiers
    return $fileType;
  }
  
  /**
   * Get all participants
   */
  private function getParticipants ($id) {
     $query = 'SELECT "civicrm_contact"."last_name" AS "civicrm_contact_last_name"
     , "civicrm_event"."id" AS "id", "event_id_civicrm_event"."id" AS "event_id_civicrm_event_id", "civicrm_contact"."id" AS "civicrm_contact_id", "civicrm_participant_status_type_civicrm_participant"."id" AS "civicrm_participant_status_type_civicrm_participant_id"
    FROM
    {civicrm_event} "civicrm_event"
    LEFT JOIN civicrm_participant "event_id_civicrm_event" ON civicrm_event.id = event_id_civicrm_event.event_id
    LEFT JOIN civicrm_contact "civicrm_contact" ON event_id_civicrm_event.contact_id = civicrm_contact.id
    LEFT JOIN civicrm_participant_status_type "civicrm_participant_status_type_civicrm_participant" ON event_id_civicrm_event.status_id = civicrm_participant_status_type_civicrm_participant.id
    WHERE ((civicrm_event.id = ' . $id . ')) AND ("civicrm_contact"."is_deleted" <> 1)
    ORDER BY "civicrm_contact_last_name" ASC'; 


     return \Drupal::database()->query($query)->fetchAll();
  }

}
