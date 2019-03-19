var mediaDirectory = {
    browse: function(modalUrl,zoneId,melisKey,params, targetDiv){
        //fake backdrop
        melisHelper.createModal(zoneId,melisKey,false,params,modalUrl, function(){
            $(".category-add-image").removeAttr('disabled ');
            $(".category-add-file").removeAttr('disabled ');

            $('.modal-dialog').draggable({
                handle: ".widget-head"
            });


            if ($(targetDiv).length > 0) {
                var categoryAddImage = $(".category-add-image");
                var currentPosition = categoryAddImage.data('currentposition');
                var categoryAddFile = $(".category-add-file");
                var currentPositionFile = categoryAddFile.data('currentposition');

                $('body').on('click','.add-image',function(event) {
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    var data = $(this).data();
                    var order = data.order;
                    var html  = null;
                    targetDiv = data.targetDiv;

                    if (data.fileType === 'image') {
                        // this is for selecting image
                        // get the current position for locating the added image when selecting
                        currentPosition = currentPosition + 1 + "image";
                        // construct element for saving the image
                        html  = "<div id='"+ currentPosition  +"' class='col-md-12 margin-b-10 category-image'>" +
                            "<img src='" + data.imageUrl + "' class='img-responsive border-green' />" +
                            "<input type='hidden' value='" + data.imageUrl + "' data-order='" + order + "'/>" +
                            "<div class='category-image-option'>" +
                            " <a class='viewImage' target='_blank' href='"+data.imageUrl+"'> <i class='fa fa-eye' ></i></a>" +
                            " <a class='removeImage' data-url='"+data.imageUrl+"' > <i class='fa fa-times' ></i></a>" +
                            "</div>" +
                            "</div>";

                        //update button attribute current position
                        $(".category-add-image").attr('currentposition', currentPosition);
                        $(targetDiv).append(html);
                        if ($("#" + currentPosition).length > 0) {
                            $("html, body").animate({ scrollTop: $("#" + currentPosition).position().top }, 100 );
                        }
                        $(".no-image").hide();
                    } else {
                        // this for selecting files
                        // get the current position for locating the added file when selecting
                        currentPositionFile = currentPositionFile + 1 + "file";
                        // construct element for saving the file
                        html = '<span id='+ currentPositionFile +' >\n' +
                                ' <a href="#" class="list-group-item list-group-item-action text-green">' + data.imageUrl + '</a>\n' +
                                ' <i class="fa fa-times-circle remove-file"></i>\n' +
                                ' <input type="hidden" value="' + data.imageUrl +'">'+
                                ' </span>\n'
                        // set the current position of the file for the next select
                        $(".category-add-file").attr('currentposition', currentPositionFile);
                        // add the file in the target div
                        $(targetDiv).append(html);
                        // scrolling to the current added file
                        if ($("#" + currentPositionFile).length > 0) {
                            $("html, body").animate({ scrollTop: $("#" + currentPositionFile).position().top }, 100);
                        }
                        // hide no file label
                        $(".no-file").hide();
                    }
                    // show/not show the button to scroll upward
                    initButtonScrollToTop();
                });
            }
        }, 'static');
        melisCoreTool.pending($(this));
    }
};
