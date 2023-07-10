<?php
/*------------------------------------------------------------------------

# Video Addon

# ------------------------------------------------------------------------

# author    Sonny

# copyright Copyright (C) 2021 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

class PlgTZ_Portfolio_PlusContentVideoViewArticle extends JViewLegacy{

    protected $item             = null;
    protected $params           = null;
    protected $video          = null;
    protected $head             = false;

    public function display($tpl = null){
        $this -> item   = $this -> get('Item');
        $this -> items   = $this -> get('VideoItems');
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $this -> params = $params;

        if(isset($this -> items) && $this -> items) {
            foreach ($this->items as $_item) {
                $this->video  =   $_item -> value;
                $this->styleInit($_item -> value);
                if(!$this -> head && isset($this->video->video_url)) {
                    $doc = JFactory::getDocument();
                    $doc -> addStyleSheet(TZ_Portfolio_PlusUri::base(true) . '/addons/content/video/css/style.css');
                    $this -> head   =   true;
                }
            }
        }
        parent::display($tpl);
    }

    protected function styleInit($item) {
        $addon_id = '#tz-portfolio-plus-video';
        $title_margin_top = (isset($item->title_margin_top) && $item->title_margin_top) ? $item->title_margin_top : '';
        $title_margin_bottom	= (isset($item->title_margin_bottom) && $item->title_margin_bottom) ? $item->title_margin_bottom : '';
        $title_color	= (isset($item->title_color) && $item->title_color) ? $item->title_color : '';
        //Css start
        $css = '';

        $title_style    =   '';
        if (isset($item->title_font) && $item->title_font) {
            $title_style     .=      TZ_Portfolio_PlusContentHelper::font_style($item->title_font);
        }
        if ($title_margin_top) {
            $title_style    .=  'margin-top:'.$title_margin_top.'px;';
        }
        if ($title_margin_bottom) {
            $title_style    .=  'margin-bottom:'.$title_margin_bottom.'px;';
        }
        if ($title_color) {
            $title_style    .=  'color:'.$title_color.';';
        }

        if($title_style) {
            $css .= $addon_id . ' .tz-video-title {';
            $css .= $title_style;
            $css .= '}';
        }

        if(isset($item->title_color_hover) && $item->title_color_hover) {
            $css .= $addon_id . ' .tz-video-title{';
            $css .= 'transition:.3s;';
            $css .='}';
            $css .= $addon_id . ':hover .tz-video-title {';
            $css .= 'color:'.$item->title_color_hover.';';
            $css .='}';
        };
        $doc = JFactory::getDocument();
        $doc->addStyleDeclaration($css);
    }

}