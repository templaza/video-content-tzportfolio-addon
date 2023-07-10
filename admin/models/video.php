<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2020 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Registry\Registry;
use TZ_Portfolio_Plus\Image\TppImageWaterMark;

jimport('joomla.filesytem.file');
JLoader::register('TZ_Portfolio_PlusFrontHelper', JPATH_SITE
    .'/components/com_tz_portfolio_plus/helpers/tz_portfolio_plus.php');

$component_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components';
// Import addon_data model
JLoader::import('com_tz_portfolio_plus.models.addon_data',$component_path);

class TZ_Portfolio_Plus_Addon_VideoModelVideo extends TZ_Portfolio_PlusModelAddon_Data{

    protected $addon_element    = 'video';

    protected function prepareTable($table){
        parent::prepareTable($table);

        $table -> element   = $this -> addon_element;

        if(!empty($table -> extension_id) && !empty($table -> content_id)){
            // Get addon data id
            if($newtable = $this -> getTable()){
                $newtable -> load(array('content_id' => $table -> content_id, 'extension_id' => $table -> extension_id));
                $table -> set('id', $newtable -> get('id'));
            }
        }
    }

    protected function loadFormData()
    {
        $data   = null;
        $_data  = parent::loadFormData();
        if(!empty($_data)){
            $data   = new stdClass();
            $data -> addon  = new stdClass();
            $data -> addon -> {$this -> addon_element}  = $_data -> value;
        }
        return $data;
    }

    function getForm($data = array(), $loadData = true){

        $form_name  = 'com_tz_portfolio_plus.'.$this -> getName();
        $form_path  = '';

        // Load addon's form
        if($addonId = $this -> getState($this -> getName().'.addon_id', Factory::getApplication()->input->getInt('addon_id'))){

            // Get a row instance.
            $table = $this->getTable('Extensions','TZ_Portfolio_PlusTable');

            // Attempt to load the row.
            $return = $table->load($addonId);

            // Check for a table object error.
            if ($return === false && $table->getError())
            {
                $this->setError($table->getError());

                return $return;
            }

            $path   = COM_TZ_PORTFOLIO_PLUS_ADDON_PATH.DIRECTORY_SEPARATOR.$table -> folder
                .DIRECTORY_SEPARATOR.$table -> element;

            $form_name  = 'com_tz_portfolio_plus.addon.'.$table -> folder.'.'.$this -> getName();
            $form_path  = $path.DIRECTORY_SEPARATOR.'admin/models/form/'.$this -> getName().'.xml';

            if(!file_exists($form_path)){
                $form_path  = $path.DIRECTORY_SEPARATOR.'admin/models/forms/'.$this -> getName().'.xml';
            }

            // Add plugin form's path
            JForm::addFormPath($path.DIRECTORY_SEPARATOR.'admin/models/form');
            JForm::addFormPath($path.DIRECTORY_SEPARATOR.'admin/models/forms');

        }

        $form = $this->loadForm($form_name
            , $this -> getName(), array('control' => 'jform', 'load_data' => $loadData));

        if(!empty($form_path) && file_exists($form_path)) {
            $form->loadFile($form_path, false);
        }

        if (empty($form)) {
            return false;
        }
        return $form;
    }
}