jQuery(function($){
    var lightboxopen = false;
    $('.tz_portfolio_plus_video .video-title').on('click', function(event) {
        event.preventDefault();
        var $pic        = $('.tz_portfolio_plus_video');
        var $clickid    = $(this).attr('data-id');
        var $index      = 0;

        var getItems = function() {
            var items = [];
            $pic.find('.video-listing').each(function() {
                var thumb       =   $(this).find('.video-title').attr('data-thumb'),
                    $href       =   $(this).find('.video-title').attr('href'),
                    $dataid     =   $(this).find('.video-title').attr('data-id'),
                    $caption    =   $(this).find('.image-caption').text();
                if ($dataid !== 'undefined' && $dataid !== null) {
                    var item = {
                        src     : $href,
                        opts    : {
                            caption : $caption,
                            thumb   : thumb
                        }
                    };
                    items.push(item);
                    if ($clickid === $dataid) $index = items.length-1;
                }
            });
            return items;
        };

        if (lightboxopen === false) {
            var items       = getItems();
            if ($(window).width()<768) {
                var instance    = $.fancybox.open(items, {
                    loop : true,
                    thumbs : {
                        autoStart : false
                    },
                    buttons: video_lightbox_buttons,
                    beforeShow: function( instance, slide ) {
                        lightboxopen = true;
                    },
                    afterClose: function( instance, slide ) {
                        lightboxopen = false;
                    }
                }, $index);
            } else {
                var instance    = $.fancybox.open(items, {
                    loop : true,
                    thumbs : {
                        autoStart : true
                    },
                    buttons: video_lightbox_buttons,
                    beforeShow: function( instance, slide ) {
                        lightboxopen = true;
                    },
                    afterClose: function( instance, slide ) {
                        lightboxopen = false;
                    }
                }, $index);
            }
        }
    });
});