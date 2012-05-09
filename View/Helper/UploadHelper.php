<?php

App::uses('AppHelper', 'View/Helper');
App::uses('ConnectionManager', 'Model');
App::uses('HtmlHelper', 'View/Html');

class UploadHelper extends AppHelper {
    public $helpers = array('Html');
    
    public function send($id = null) {
        
        $view = $this->_View;
        $this->Html->css('/upload/css/uploadify',false,array('inline'  => false));
        $this->Html->script('/upload/js/swfobject',false);
        $this->Html->script('/upload/js/jUploadify',false);
        
        $html = $view->element('upload', array('id' => $id), array('plugin' => 'Upload'));
        
        return $html;
    }
}
