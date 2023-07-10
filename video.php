<?php
/*------------------------------------------------------------------------

# Music Addon

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2016 tzportfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum

# Family website: http://www.templaza.com

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\Utilities\ArrayHelper;

class PlgTZ_Portfolio_PlusContentVideo extends TZ_Portfolio_PlusPlugin
{
    protected $autoloadLanguage = true;

    public function onContentAfterSave($context, $data, $isnew){
        if($context == 'com_tz_portfolio_plus.article' || $context == 'com_tz_portfolio_plus.form') {


            if($model  = $this -> getModel($this -> _name, 'TZ_Portfolio_Plus_Addon_'.$this -> _name.'Model')) {
                if(method_exists($model,'save')) {
                    if(isset($this -> _myFormDataBeforeSave) && !empty($this -> _myFormDataBeforeSave)){

                        $addon      = TZ_Portfolio_PlusPluginHelper::getPlugin($this -> _type, $this -> _name);
                        $mydata     = array('value' => $this -> _myFormDataBeforeSave);

                        if(!empty($addon) && isset($addon -> id)){
                            $mydata['extension_id'] = $addon -> id;
                            if(!empty($data) && isset($data -> id)){
                                $mydata['content_id']   = $data -> id;
                            }
                            $mydata['published']    = 1;

                            $model->save( $mydata);
                        }
                    }
                }
            }
        }

    }
    public function onAfterDisplayAdditionInfo($context, &$article, $params, $page = 0, $layout = 'default'
        , $module = null){

        list($extension, $vName) = explode('.', $context);

        $item = $article;

        if ($extension == 'module' || $extension == 'modules') {
            if ($path = $this->getModuleLayout($this->_type, $this->_name, $extension, $vName, $layout)) {
                // Display html
                ob_start();
                include $path;
                $html = ob_get_contents();
                ob_end_clean();
                $html = trim($html);
                return $html;
            }
        }elseif(in_array($context, array('com_tz_portfolio_plus.portfolio', 'com_tz_portfolio_plus.date'
        , 'com_tz_portfolio_plus.featured', 'com_tz_portfolio_plus.tags', 'com_tz_portfolio_plus.users'))){
            if($html = $this -> _getViewHtml($context,$item, $params)){
                return $html;
            }
        }
    }


    /*
     * Register form with position to article form
     * @article: the article data.
     * Return form with position:
     *  -title: title of form to display in article form
     *  -html: html of form to display in article form
     *  -position: position (description, before_description or after_description) to display in article form
     * */
//    public function onAddFormBeforeArticleDescription($article = null){
//        $position   = $this -> __addFormToPosition($article);
//        return $position;
//    }

    protected function __addFormToPosition($article = null, $position = 'before_description'){
        $_position  = new stdClass();
        $lang       = Factory::getApplication() -> getLanguage();
        $lang_key   = 'PLG_' . $this->_type . '_' . $this->_name . '_TITLE';
        $lang_key   = strtoupper($lang_key);
        $model      = null;
        $this -> form   = null;

        if ($lang->hasKey($lang_key)) {
            $_position -> title = JText::_($lang_key);
        } else {
            $_position -> title = $this->_name;
        }

        $_position -> addon  = $this->_name;
        $_position -> group  = $this->_type;

        $_position -> position   = $position;

        if($model = $this -> getModel($this -> _name, 'TZ_Portfolio_Plus_Addon_'.ucfirst($this -> _name).'Model')) {
            // Get addon info
            $addon      = TZ_Portfolio_PlusPluginHelper::getPlugin($this -> _type, $this -> _name);

            $model->setState($this->_name . '.addon_id', $addon -> id);

            $table  = $model -> getTable();
            if($table -> load(array('extension_id' => $addon -> id, 'content_id' => $article -> id))) {
                $model->setState($this->_name . '.id', (int)$table->get('id'));

                $properties = $table->getProperties(1);
                $data = ArrayHelper::toObject($properties, '\JObject');

                if($data && isset($data -> value) && is_string($data -> value)){
                    $data -> value  = json_decode($data -> value);
                }
            }

            $path           = TZ_Portfolio_PlusPluginHelper::getLayoutPath($this -> _type, $this -> _name, 'admin');

            if(method_exists($model, 'getForm')) {
                $this->form = $model->getForm();
            }else {
                $this->form->loadFile(COM_TZ_PORTFOLIO_PLUS_ADDON_PATH . '/' . $this->_type . '/' . $this->_name
                    . '/admin/models/forms/' . $this->_name . '.xml', false);
            }

            if(!empty($this -> form)){
                $_data   = new stdClass();
                $_data -> addon  = new stdClass();
                if(isset($data)) {
                    $_data->addon->{$this->_name} = $data->value;
                }
                $this -> form -> bind($_data);
            }

            $this -> item   = $article;
            if(File::exists($path) && isset($this -> form) && $this -> form) {
                ob_start();
                require $path;
                $content = ob_get_contents();
                ob_end_clean();
                $_position -> html = $content;
            }

        }

        return $_position;
    }
    public function onAddFormAfterArticleDescription($article = null){
        $position   = $this -> __addFormToPosition($article);
        return $position;
    }
//    public function onAddFormToArticleDescription($article = null){
//        $position   = $this -> __addFormToPosition($article);
//        return $position;
//    }
}