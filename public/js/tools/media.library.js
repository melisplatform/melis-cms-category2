var mediaDirectory = {
    browse: function(modalUrl,zoneId,melisKey,params, targetDiv){
        melisHelper.createModal(zoneId,melisKey,false,params,modalUrl, function(){
            $(".category-add-image").removeAttr('disabled ');
            $(".category-add-file").removeAttr('disabled ');

            if ($(targetDiv).length > 0) {
                $('body').on('click','.add-image',function(event) {
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    var data = $(this).data();
                    var order = data.order;
                    var html  = null;
                    targetDiv = data.targetDiv;
                    if (data.fileType === 'image') {
                        html  = "<div class='col-md-12 margin-b-10 category-image'>" +
                            "<img src='" + data.imageUrl + "' class='img-responsive' />" +
                            "<input type='hidden' value='" + data.imageUrl + "' data-order='" + order + "'/>" +
                            "<div class='category-image-option'>" +
                            " <a class='viewImage' target='_blank' href='"+data.imageUrl+"'> <i class='fa fa-eye' title='View image'></i></a>" +
                            " <a class='removeImage' data-url='"+data.imageUrl+"' > <i class='fa fa-times' title='Delete image'></i></a>" +
                            "</div>" +
                            "</div>";
                        //scroll down
                        $("html, body").animate({ scrollTop: $(document).height()- $(window).height() });
                    } else {
                        html = "<div class='col-md-3'>" +
                                "<div class=\"file-area\">" +
                                " <span class=\"fa fa-file file-icon\"></span>" +
                                " <span class=\"description\">colipano1.docx</span>" +
                                " </div>" +
                                "<input type='hidden' value='" + data.imageUrl + "' data-order='" + order + "'/>" +
                                "<div class='category-image-option'>" +
                                "<a class='viewImage' target='_blank' href='"+data.imageUrl+"'> <i class='fa fa-eye' title='View file'></i></a>" +
                                "<a class='removeImage' data-url='"+data.imageUrl+"' > <i class='fa fa-times' title='Delete file'></i></a>" +
                                "</div>" +
                               "</div>";
                        html = '<span>\n' +
                                ' <a href="#" class="list-group-item list-group-item-action">' + data.imageUrl + '</a>\n' +
                                ' <i class="fa fa-times-circle remove-file"></i>\n' +
                                ' <input type="hidden" value="' + data.imageUrl +'">'+
                                ' </span>\n'

                    }

                    // remove no image
                    $(".no-image").hide();
                    $(targetDiv).append(html);
                });

            }
        });
        melisCoreTool.pending($(this));
    }
};
