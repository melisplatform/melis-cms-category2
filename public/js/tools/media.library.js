var mediaDirectory = {
    browse: function(modalUrl,zoneId,melisKey,params, targetDiv){
        melisHelper.createModal(zoneId,melisKey,false,params,modalUrl, function(){
            $(".category-add-image").removeAttr('disabled ');
            if ($(targetDiv).length > 0) {
                console.log(params);
                $('body').on('click','.add-image',function(event) {
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    var data = $(this).data();
                    var order = data.order;
                    var image = "<div class='col-md-12 margin-b-10 category-image'>" +
                            "<img src='" + data.imageUrl + "' class='img-responsive' />" +
                            "<input type='hidden' value='" + data.imageUrl + "' data-order='" + order + "'/>" +
                            "<div class='category-image-option'>" +
                                " <a class='viewImage' target='_blank' href='"+data.imageUrl+"'> <i class='fa fa-eye' title='View image'></i></a>" +
                                " <a class='removeImage' data-url='"+data.imageUrl+"' > <i class='fa fa-times' title='Delete image'></i></a>" +
                            "</div>" +
                        "</div>";
                    // remove no image
                    $(".no-image").hide();
                    $(targetDiv).html(image);
                    //scroll down
                    $("html, body").animate({ scrollTop: $(document).height()- $(window).height() });
                });

            }
        });
        melisCoreTool.pending($(this));
    }
};
