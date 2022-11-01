<?php
// True File Manager Module
// Copyright (c) 2019 sitecreator.ru
//Копирование и распространение без согласия разработчика (sitecreator.ru) не допускается.
class ControllerCommonFileManager extends ControllerCommonFileManager__ {
  public function index() {
    $oc23 = (version_compare(VERSION, "2.3", ">="))? true:false;
    $oc15 = (version_compare(VERSION, "2.0", "<"))? true:false;
    $oc30 = (version_compare(VERSION, "3.0", ">="))? true:false;

    $data = [];
    $data['token'] = $this->session->data['token'];

    // Return the target ID for the file manager to set the value
    if (isset($this->request->get['target'])) {
      $data['target'] = $this->request->get['target'];
    } else {
      $data['target'] = '';
    }

    // CKEditor
    if (isset($this->request->get['cke'])) {
      $data['cke'] = $this->request->get['cke'];
    } else {
      $data['cke'] = '';
    }

    // summernote_id
    if (isset($this->request->get['summernote_id'])) {
      $data['summernote_id'] = $this->request->get['summernote_id'];
    } else {
      $data['summernote_id'] = '';
    }

    // Return the thumbnail for the file manager to show a thumbnail
    if (isset($this->request->get['thumb'])) {
      $data['thumb'] = $this->request->get['thumb'];
    } else {
      $data['thumb'] = '';
    }

    $tpl = "common/truefilemanager.tpl";
    if($oc23) $tpl = "common/truefilemanager";

    $this->response->setOutput($this->load->view($tpl, $data));
  }

  public function connector() {

    require_once DIR_SYSTEM. "library/sitecreator/elFinder/php/elFinderConnector.class.php";
    require_once DIR_SYSTEM. "library/sitecreator/elFinder/php/elFinder.class.php";
    require_once DIR_SYSTEM. "library/sitecreator/elFinder/php/elFinderVolumeDriver.class.php";
    require_once DIR_SYSTEM. "library/sitecreator/elFinder/php/elFinderVolumeLocalFileSystem.class.php";

    if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
      $domain = (defined('HTTPS_CATALOG'))? HTTPS_CATALOG: HTTPS_SERVER;
    else {$domain = (defined('HTTP_CATALOG'))? HTTP_CATALOG: HTTP_SERVER;}

    $opts = array(
      'roots' => array(
        array(
          'driver' => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
          'path'   => DIR_IMAGE. 'catalog/',                // path to files (REQUIRED)
          'URL'    => $domain. 'image/catalog/',
          'tmbPath'=> DIR_IMAGE. 'elfinder_tmb',
          'tmbURL' => $domain. 'image/elfinder_tmb/',
          'tmbSize' => 100,
          'tmbCrop' => false,
          'tmbBgColor' => '#ffffff',
          'mimeDetect' => 'internal',
          'imgLib'     => 'auto',
          'winHashFix' => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
          'uploadAllow' => array('image/jpeg', 'image/png', 'image/gif', 'image/svg+xml',
            // "особые" типы древних IE добавил на всякий случай sitecreator
            'image/pjpeg', 'image/x-png'),
          'uploadDeny' => array('all'),
          'uploadOrder' => array('allow, deny'),
        )

      )
    );


    $connector = new elFinderConnector(new elFinder($opts), true);
    $connector->run();

  }

}

?>