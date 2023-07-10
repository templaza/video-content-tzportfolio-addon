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

$params = $this->params;
if ($params->get('mt_video_show',1)) {
    if (isset($this->video) && $video = $this->video) {
        if (isset($video->video_url) && $video->video_url) {
            //Title
            $title = (isset($video->title) && $video->title) ? $video->title : '';
            $heading_selector = (isset($video->title_element) && $video->title_element) ? $video->title_element : 'h3';

            //Custom Class
            $custom_class = (isset($video->custom_class) && trim($video->custom_class)) ? $video->custom_class : '';

            //Options
            $url = (isset($video->video_url) && $video->video_url) ? $video->video_url : '';
            $autoplay = (isset($video->autoplay)) ? $video->autoplay : 0;
            $loop = (isset($video->loop)) ? $video->loop : 0;
            $muted = (isset($video->muted)) ? $video->muted : 0;
            $autopause = (isset($video->autopause)) ? $video->autopause : 1;
            $byline = (isset($video->byline)) ? $video->byline : 1;
            $video_title = (isset($video->video_title)) ? $video->video_title : 1;
            $portrait = (isset($video->portrait)) ? $video->portrait : 1;
            $controls = (isset($video->controls)) ? $video->controls : 1;
            $no_cookie = (isset($video->no_cookie) && $video->no_cookie) ? $video->no_cookie : 0;
            $show_rel_video = (isset($video->show_rel_video) && $video->show_rel_video) ? '&rel=1' : '&rel=0';
            $attrb[]  = 'autoplay='.$autoplay;
            $attrb[]  = 'loop='.$loop;
            $attrb[]  = 'muted='.$muted;
            $attrb[]  = 'autopause='.$autopause;
            $attrb[]  = 'title='.$video_title;
            $attrb[]  = 'byline='.$byline;
            $attrb[]  = 'portrait='.$portrait;
            $attrb[]  = 'controls='.$controls;
            if($url) {
                $video = parse_url($url);

                $youtube_no_cookie = $no_cookie ? '-nocookie' : '';

                switch($video['host']) {
                    case 'youtu.be':
                        $id = trim($video['path'],'/');
                        $src = '//www.youtube'.$youtube_no_cookie.'.com/embed/' . $id .'?iv_load_policy=3'.$show_rel_video.'&amp;'.implode('&amp;', $attrb);;
                        break;

                    case 'www.youtube.com':
                    case 'youtube.com':
                        parse_str($video['query'], $query);
                        $id = $query['v'];
                        $src = '//www.youtube'.$youtube_no_cookie.'.com/embed/' . $id .'?iv_load_policy=3'.$show_rel_video.'&amp;'.implode('&amp;', $attrb);;
                        break;

                    case 'vimeo.com':
                    case 'www.vimeo.com':
                        $id = trim($video['path'],'/');

                        $src = "//player.vimeo.com/video/{$id}?".implode('&amp;', $attrb);
                }

            }

            echo '<div id="tz-portfolio-plus-video" class="'.$custom_class.'">';
            if($title) {
                echo '<'.$heading_selector.' class="tz-addon-title tz-video-title">';
                echo $title;
                echo '</'.$heading_selector.'>';
            }
            echo '<div class="tz_portfolio_plus_video tz-embed-responsive tz-embed-responsive-16by9">';
            echo '<iframe class="tz-embed-responsive-item" ' . 'src="'.$src.'"' . ' webkitAllowFullScreen mozallowfullscreen allowFullScreen loading="lazy"></iframe>';
            echo '</div>';
            echo '</div>';
        }
    }
}
