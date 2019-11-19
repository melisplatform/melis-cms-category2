$(function() {
    var $body = $("body");

        // deleteing category media image
        $body.on('click', ".category-image-list .removeImage", function() {
            var $this           = $(this),
                countImage      = $(".category-image-list").children('.category-image').length,
                parentDiv       = $this.parent().parent(),
                thisButton      = this,
                deleteTitle     = translations.tr_meliscms_categories_confirm_delete_image_title,
                deleteMessage   = translations.tr_meliscms_categories_confirm_delete_image,
                category2Id     = $(".removeImage").data('categoryid');

                melisCoreTool.pending(this);

                melisCoreTool.confirm(
                    translations.tr_meliscore_common_yes,
                    translations.tr_meliscore_common_no,
                    deleteTitle,
                    deleteMessage, function() {
                        var dataString = {
                            imageName   : parentDiv.find('input').val(),
                            fileType    : 'image',
                            categoryId  : category2Id
                        };

                        $.ajax({
                            type        : "POST",
                            url         : "/melis/MelisCmsCategory2/MelisCmsCategoryMedia/deleteFile",
                            data		: dataString
                        }).done(function(data) {
                            $(thisButton).button("reset");
                            if ( data.success ) {
                                melisHelper.melisOkNotification(data.textTitle, data.textMessage);
                                var tmpZoneId 	= 'id_meliscategory_category_tab_media_content_left_image_list',
                                    tmpMeliskey = 'meliscategory_category_tab_media_content_left_image_list';
                                    
                                    // zonereload
                                    melisHelper.zoneReload(tmpZoneId, tmpMeliskey,{ catId:category2Id });

                            } else {
                                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
                            }
                            melisCore.flashMessenger();

                        }).fail(function() {
                            $(thisButton).button("reset");
                            alert( translations.tr_meliscore_error_message );
                        });
                });
        });

        // deleting media file
        $body.on('click', ".category-file .remove-file", function() {
            var $this           = $(this),
                countImage      = $(".category-file-list .list-group").children('span').length,
                parentDiv       = $this.parent(),
                thisButton      = this,
                deleteTitle     = translations.tr_meliscms_categories_confirm_delete_file_title,
                deleteMessage   = translations.tr_meliscms_categories_confirm_delete_file,
                category2Id     = $(".remove-file").data('categoryid');

                melisCoreTool.confirm(
                    translations.tr_meliscore_common_yes,
                    translations.tr_meliscore_common_no,
                    deleteTitle,
                    deleteMessage,
                    function() {

                        var dataString = {
                            imageName   : parentDiv.find('input').val(),
                            fileType    : 'file',
                            categoryId  : category2Id
                        };

                        $.ajax({
                            type        : "POST",
                            url         : "/melis/MelisCmsCategory2/MelisCmsCategoryMedia/deleteFile",
                            data		: dataString
                        }).done(function(data) {
                            $(thisButton).button("reset");

                            if ( data.success ) {
                                melisHelper.melisOkNotification(data.textTitle, data.textMessage);

                                var tmpZoneId 	= 'id_meliscategory_category_tab_media_content_right_file',
                                    tmpMeliskey = 'meliscategory_category_tab_media_content_right_file';
                                    
                                    // zonereload
                                    melisCore.flashMessenger();
                                    melisHelper.zoneReload(tmpZoneId, tmpMeliskey,{ catId:category2Id });
                            }
                            else {
                                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
                            }
                        }).fail(function() {
                            $(thisButton).button("reset");
                            alert( translations.tr_meliscore_error_message );
                        });
                })
        });

        $body.on('click','.viewImage', function(e) {
            e.preventDefault();

            var $this               = $(this),
                parent              = $this.parent(),
                currentImageValue   = parent.parent().find('input').val(),
                images              = "",
                panelHeight         = $('#cmsCategoryInfoPanel .card-body').height() + 290;

                $('.viewImage').parent().parent().find('input').each(function(i,val) {
                    var tmpVal = $(val).val();

                        if ( currentImageValue === tmpVal ) {
                            images = images + "<img src='" + tmpVal+ "' class='active'/>";
                        }
                        else {
                            images = images + "<img src='" + tmpVal+ "'/>";
                        }
                });
                
                var backDrop = "<div class='back-drop' style='height:"+ panelHeight+ "px'>" +
                                    "<div class='preview-image'>" +
                                        "<span></span>" +
                                    "</div>" +
                                "</div>";

                    $body.append(backDrop).fadeIn('fast');

                var span =  $(".back-drop .preview-image span");

                    span.append(images);
                    span.append("<div class='close-images'></div>");
                    $("html, body").animate({ scrollTop: 50 }, 100);
        });

        $body.on('click','.close-images',function() {
            $('.back-drop').fadeOut('fast', function() { $('.back-drop').remove() });
        });

        $body.on('click','.back-drop',function(e) {
            e.stopPropagation();

            var a = $(this);

                $(a).fadeOut('medium', function(){ $(a).remove()});
        });
});