/* Category Sticky Top Start */
if( melisCore.screenSize >= 768){
	$(window).on('scroll click resize', function(e) {
		$("#id_meliscategory_categories_category_header").css("width","100%");
		var stickyCatNav = $("#"+ activeTabId + ' #id_meliscategory_categories_category');
		if(stickyCatNav.length){
			var position = stickyCatNav.position();
			if (position.top < ($(window).scrollTop() - 10)) {
				$("#id_meliscategory_categories_category").addClass("fix-cat");
				$("#categoryInfoPanel").css("padding-top","66px");
				$("#saveCategory").css("margin-top","10px");
				$("#id_meliscategory_categories_category_header").width($("#id_meliscategory_categories_list").width());
			} else {
				$("#id_meliscategory_categories_category").removeClass("fix-cat");
				$("#categoryInfoPanel").css("padding-top","0");
				$("#saveCategory").css("margin-top","0");
			}
		}		
	});
}
/* Category Sticky Top End */

var categoryOpeningItemFlag = true;
$(function(){
	var categoryBody = $("body");
	$("body").on("click", ".addCategory", function(e){ 
		$("#categoryTreeViewPanel").collapse("hide");
		var zoneId = 'id_meliscategory_categories_category';
		var melisKey = 'meliscategory_categories_category';
		var catTree = $('#categoryTreeView').jstree(true);
		var catSelected = catTree.get_selected();
		var catFatherId = '';
		if(catSelected.length >= 1){
			/**
			 * using parseInt this will get only the
			 * number value in a string value
			 */
			catFatherId = parseInt(catSelected[0]);
		} else {
            catFatherId = -1;
		}
		$("#"+zoneId).removeClass("hidden");
		melisHelper.zoneReload(zoneId, melisKey, {catId : 0, catFatherId: catFatherId});
	});
	
	$("body").on("click", ".addCatalog", function(e){ 
		$("#categoryTreeViewPanel").collapse("hide");
		var zoneId = 'id_meliscategory_categories_category';
		var melisKey = 'meliscategory_categories_category';
		$("#"+zoneId).removeClass("hidden");
		melisHelper.zoneReload(zoneId, melisKey, {catId : 0, catFatherId: -1});
	});
	
	$("body").on("click", "#saveCategory", function(){
		
		$(this).button("loading");
		var catId = $(this).data('catid');
		var dataString = new Array;
		// Serialize Forms of Category Panel

		dataString = $("#id_meliscategory_categories_category form").not(".category_"+catId+"_seo_form, .cat_trans_form").serializeArray()
		// Category Id
		dataString.push({
			name : "cat_id",
			value: catId
		});
		// Category Parent Id
		var catFatherId = $(this).data('catfatherid');
		dataString.push({
			name : "cat_father_cat_id",
			value: catFatherId
		});
		// Category Status
		var catStatus = 0;
		if($('input[name="cat_status"]').is(':checked')){
			catStatus = 1;
		}
		
		dataString.push({
			name : "cat_status",
			value: catStatus
		});
		// save media
		//image
        $("#id_meliscategory_category_tab_media_content_left").find('input').each(function(index){
            dataString.push({
                name: "cat2_media_image["+ index + "]",
                value : $(this).val()
            });
        });

        $("#id_meliscategory_category_tab_media_content_right").find('input').each(function(index){
            dataString.push({
                name: "cat2_media_file["+ index+"]",
                value : $(this).val()
            });
        });


		// Category Transalations
		$("form.cat_trans_form").each(function(){
			langLocale = $(this).data("locale");
			langId = $(this).data("langid");
			
			// convert the serialized form values into an array
			catDataString = $(this).serializeArray();
			
			$.each(catDataString, function(){
				dataString.push({
					name : 'cat_trans['+langId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		// serialize the new array and send it to server
		dataString = $.param(dataString);
		
		$.ajax({
	        type        : "POST",
	        url         : "/melis/MelisCmsCategory2/MelisCmsCategory/saveCategory",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true,
	        cache		: false,
		}).done(function(data) {
			
			$("#saveCategory").button("reset");
			
			if(data.success) {
				$("#categoryTreeViewPanel").collapse("show");
				
				$("body").animate({
			        scrollTop: 0
			    }, 1000); 
				
				melisCore.flashMessenger();
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
				
				var catTree = $('#categoryTreeView').jstree(true);
				// Get Current Url of the category Tree view
				var realUrl = catTree.settings.core.data.url;
				
				// selected Category Id/Node
				var selectedNode = '';
				if(catId==0){
					// New Category Created
					var nodeData = catTree.get_node(catFatherId);
					
					var nodeParents = new Array;
					
					nodeParentsStr = '';
					
					nodeParents.push(catFatherId);
					
					if(typeof nodeData === "object"){
						if(nodeData.parents.length>1){
							for(i = 0; i<nodeData.parents.length-1 ; i++){
								nodeParents.push(nodeData.parents[i]);
							}
						}
					}
					
					nodeParentsStr = "&openStateParent="+nodeParents.join();
					
					selectedNode = data.cat_id;
					
				}else{
					// Category exist
					var nodeData = catTree.get_node(catId);
					
					var nodeParents = new Array;
					
					nodeParentsStr = ''; 
					
					if(nodeData !== false){
						if(nodeData.parents.length>1 ){
							for(i = 0; i<nodeData.parents.length-1 ; i++){
								nodeParents.push(nodeData.parents[i]);
							}
							nodeParentsStr = "&openStateParent="+nodeParents.join();
						}
						
						catTree.get_node(catId).state.selected = true;
					}
					
					selectedNode = catId;
				}
				
				// Set JsTree Url with Selected Node and Open State Nodes
				catTree.settings.core.data.url = realUrl+"&selected="+selectedNode+nodeParentsStr;
				// Deselect selected node
				catTree.deselect_all();
				// Remove Node Highllight
				$("#categoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
				//refresh Category view
				catTree.refresh();
				// Rollback the real/default url
				catTree.settings.core.data.url = realUrl;
				
	    		var zoneId = 'id_meliscategory_categories_category';
	    		var melisKey = 'meliscategory_categories_category';
	    		melisHelper.zoneReload(zoneId, melisKey, {catId : selectedNode});
	    		
	    		// Highlighting the node
	    		$("#categoryTreeView #"+selectedNode+" div").first().addClass("jstree-wholerow-clicked");
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscategory_categories_category_form_transalations");
			}
			
			melisCore.flashMessenger();
			
		}).fail(function(){
			
			$("#saveCategory").button("reset");
			
			alert( translations.tr_meliscore_error_message);
		});
	});
	
	// Category Tree Languages Dropdown
	$("body").on("click", ".category-tree-view-lang li a", function(){
		categoryOpeningItemFlag = false;
		var langText = $(this).text();
		var langLocale = $(this).data('locale');
		$('.cat-tree-view-languages span.filter-key').text(langText);
        // disable buttons
        $("#categoryTreeViewSearchInput").attr("disabled","disabeld");
        $(".category-list-lang-dropdown").attr("disabled","disabeld");
        $(".category-list-lang-dropdown").css("cursor","not-allowed");
        $("#clearSearchInputBtn").attr("disabled","disabeld");
        $("#collapseCategoryTreeViewBtn").attr("disabled","disabeld");
        $("#expandCategoryTreeViewBtn").attr("disabled","disabeld");
        $("#refreshCategoryTreeView").attr("disabled","disabeld");
        var categorySiteFilter = $("#categorySiteFilter");
        categorySiteFilter.attr("disabled","disabeld");

		$("#categoryTreeView").data('langlocale',langLocale);
		$("#categoryTreeView").jstree(true).settings.core.data.data = [{name : "langlocale", value: langLocale}, {name:"siteId", value : categorySiteFilter.val()}];
		$("#categoryTreeView").jstree(true).refresh();
	});

	$("body").on('change',"#categorySiteFilter", function(){
		var value = this.value;
		var cmsCategoryTree = $("#categoryTreeView");
		var langLocale = cmsCategoryTree.data('langlocale');
		if (typeof(cmsCategoryTree.jstree(true).settings) !== "undefined" ) {
            $(this).attr("disabled","disabeld");
            // disable buttons
            $("#categoryTreeViewSearchInput").attr("disabled","disabeld");
            $(".category-list-lang-dropdown").attr("disabled","disabeld");
            $(".category-list-lang-dropdown").css("cursor","not-allowed");
            $("#clearSearchInputBtn").attr("disabled","disabeld");
            $("#collapseCategoryTreeViewBtn").attr("disabled","disabeld");
            $("#expandCategoryTreeViewBtn").attr("disabled","disabeld");
            $("#refreshCategoryTreeView").attr("disabled","disabeld");

            cmsCategoryTree.jstree(true).settings.core.data.data = [{name : "langlocale", value: langLocale},{name:"siteId", value : value}];
            cmsCategoryTree.jstree(true).refresh();
		}
	});

	// Search Input
	$("body").on("keyup", "#categoryTreeViewSearchInput", function(e){ 
		categoryOpeningItemFlag = false;
		
		var searchString = $(this).val().trim();
		var searchResult = $('#categoryTreeView').jstree('search', searchString);
		
		setTimeout(function(){ 
			if($(searchResult).find('.jstree-search').length == 0 && searchString != ''){
				$("#searchNoResult").removeClass('hidden');
				$("#searchNoResult").find("strong").text(searchString);
			}else{
				$("#searchNoResult").addClass('hidden');
			}
		}, 1500);
		
	});
	
	$("body").on('keyup keypress', '#categoryTreeViewSearchForm', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) { 
		    e.preventDefault();
		    return false;
		}
	});
	
	// Clear Input Search
	$("body").on("click", "#clearSearchInputBtn", function(e){ 
		categoryOpeningItemFlag = false;
		var catTree = $('#categoryTreeView').jstree(true);
		$("#categoryTreeViewSearchInput").val("");
		$('#categoryTreeView').jstree('search', '');
	});
	
	// Toggle Buttons for Category Tree View
	$("body").on("click", "#expandCategoryTreeViewBtn", function(e){ 
		categoryOpeningItemFlag = false;
		$("#categoryTreeView").jstree("open_all");
	});
	$("body").on("click", "#collapseCategoryTreeViewBtn", function(e){ 
		categoryOpeningItemFlag = false;
		$("#categoryTreeView").jstree("close_all");
	});
	
	// Refrech Category Tree View
	$("body").on("click", "#refreshCategoryTreeView", function(e){ 
		categoryOpeningItemFlag = false;
		var catTree = $('#categoryTreeView').jstree(true);
		catTree.deselect_all();
		catTree.refresh();
		$("#categoryTreeViewSearchInput").val("");
		$('#categoryTreeView').jstree('search', '');
	});
	
	// Category Information Form Countries Custom Checkboxes
	$("body").on("click", ".cms-category-checkbox", function(evt){
		
		if($(this).find('.fa').hasClass('fa-check-square-o')){ // unchecking category Checkbox
			$(this).find('.fa').removeClass('fa-check-square-o');
			$(this).find('.fa').addClass('fa-square-o');
			$(this).find('input[type="checkbox"]').removeAttr('checked');
			
			// If the uncheck is check all checkbox
			if($(this).find('.check-all').hasClass('fa-square-o')){
				$(".cms-category-checkbox .fa").not(".check-all").addClass('fa-square-o');
				$(".cms-category-checkbox .fa").not(".check-all").removeClass('fa-check-square-o');
				$(".cms-category-checkbox .fa").not(".check-all").next('input[type="checkbox"]').removeAttr('checked');
			}
			
		}else{ // Checking Category Checkboxes
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square-o');
			$(this).find('input[type="checkbox"]').attr('checked','checked');
		}
		
		// check all countries
		if($(".cms-category-checkbox .fa").not(".check-all").length == $(".cms-category-checkbox .fa.fa-check-square-o").not(".check-all").next('input[type="checkbox"]:checked').length || $(this).find('.check-all').hasClass('fa-check-square-o')){
			
			// Keeping the check mark but removing the checkbox unchecked
			$(".cms-category-checkbox .fa").not(".check-all").removeClass('fa-square-o');
			$(".cms-category-checkbox .fa").not(".check-all").addClass('fa-check-square-o');
			$(".cms-category-checkbox .fa").not(".check-all").next('input[type="checkbox"]').removeAttr('checked');
			
			// Check mark on checkbox all ang its input checkbox
			$(".cms-category-checkbox .fa.check-all").removeClass('fa-square-o');
			$(".cms-category-checkbox .fa.check-all").addClass('fa-check-square-o');
			$(".cms-category-checkbox .fa.check-all").next('input[type="checkbox"]').attr('checked','checked');
		}else{
			
			// puting back checkbox with check mark to input checkbox checked
			$(".cms-category-checkbox .fa.fa-check-square-o").not(".check-all").next('input[type="checkbox"]').attr('checked','checked');
			
			// Unchecking "check all" checkbox
			$(".cms-category-checkbox .fa.check-all").addClass('fa-square-o');
			$(".cms-category-checkbox .fa.check-all").removeClass('fa-check-square-o');
			$(".cms-category-checkbox .fa.check-all").next('input[type="checkbox"]').removeAttr('checked');
		}
		
		evt.stopPropagation();
		evt.preventDefault();
	});
	
	// Category Information Form Status, Switch Plugin
	$("body").on("switch-change", "#cat_status", function(event, state) {
		if(state.value == true){
			$(this).find('input[type="checkbox"]').attr('checked','checked');
		}else{
			$(this).find('input[type="checkbox"]').removeAttr('checked');
		}
	});

	
	// Category Tree Double Click Item Action
	$("body").on("dblclick", ".jstree-node", function(evt){
		
		$("#categoryTreeViewPanel").collapse("hide");
		
		var catId = parseInt($(this).attr("id"), 10);
    	
		var zoneId = 'id_meliscategory_categories_category';
		var melisKey = 'meliscategory_categories_category';
		
		$("#"+zoneId).removeClass("hidden");
		
		melisHelper.zoneReload(zoneId, melisKey, {catId : catId});
		
		// Highlighting the node
		$("#categoryTreeView #"+catId+" div").first().addClass("jstree-wholerow-clicked");

		evt.stopPropagation();
		evt.preventDefault();
	});
	
	// Open Single Node in JSTree
	$("body").on("click", ".cat-div .jstree-node .jstree-icon", function(){
		categoryOpeningItemFlag = true;
	});
	
    $("body").on("mouseenter mouseout", ".toolTipCatHoverEvent", function(e) {
      $(".thClassColId").attr("style", "");
  	  var productId = $(this).data("productid");
  	  var loaderText = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
  	  $.each($("table#catProductTable"+productId + " thead").nextAll(), function(i,v) {
  		  $(v).remove();
  	  });
  	  $(loaderText).insertAfter("table#catProductTable"+productId + " thead");
  		var xhr = $.ajax({
  	        type        : 'POST', 
  	        url         : 'melis/MelisCommerce/MelisComProductList/getToolTip',
  	        data		: {productId : productId},
  	        dataType    : 'json',
  	        encode		: true,
  	     }).success(function(data){
      	 	 $("div.qtipLoader").remove();
  		     if(data.content.length === 0) {
  		    	 $('<div class="qtipLoader"><hr/><span class="text-center col-lg-12">'+translations.tr_meliscategory_product_tooltip_no_variants+'</span><br/></div>').insertAfter("table.qtipTable thead");
  		     }
  		     else {
  		    	 // make sure tbody is clear
  				  $.each($("table#catProductTable"+productId + " thead").nextAll(), function(i,v) {
  					  $(v).remove();
  				  });
      		     $.each(data.content.reverse(), function(i ,v) {
      		    	 $(v).insertAfter("table#catProductTable"+productId + " thead")
      		     });
  		    	 
  		     }

  	     });
  		if(e.type === "mouseout") {
  			xhr.abort();
  		}
  	  });

     // add image
    categoryBody.on('click', ".category-add-image" , function(){
    	var catv2ImageZoneId   = "id_meliscategory_mini_media_library";
    	var catv2ImageMelisKey = "meliscategory_mini_media_library";
        var categoryv2ModalUrl = '/melis/MelisCmsCategory2/MelisCmsCategoryMedia/render-mini-media-modal-container';
        var data = $(this).data();
        melisCoreTool.pending($(this));
		mediaDirectory.browse(categoryv2ModalUrl,catv2ImageZoneId,catv2ImageMelisKey,{
				fileType  : data.type,
				targetDiv : ".category-image-list",
				currentPosition : data.currentposition
			}, ".category-image-list")
        $(".parent-file-list .back-drop").fadeIn("fast");
	});

    categoryBody.on('click', ".category-add-file" , function(){
        var catv2ImageZoneId   = "id_meliscategory_mini_media_library";
        var catv2ImageMelisKey = "meliscategory_mini_media_library";
        var categoryv2ModalUrl = '/melis/MelisCmsCategory2/MelisCmsCategoryMedia/render-mini-media-modal-container';
        var data = $(this).data();
        melisCoreTool.pending($(this));
        mediaDirectory.browse(categoryv2ModalUrl,catv2ImageZoneId,catv2ImageMelisKey,{
        	fileType  : data.type,
			targetDiv : ".category-file-list .list-group",
            currentPosition : data.currentposition
		},".category-file-list")
		$(".parent-image-list .back-drop").fadeIn("fast");

    });
    categoryBody.on('submit',"#id_meliscategory_media_upload_form",function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        melisCoreTool.pending(this);
        var dataElem = $(".category-add-image").data() ;
		$(".media-upload-loader").fadeIn('medium');
		var mediaType = $("#category-upload-media").data('type');
		var targetArea = $("#category-upload-media").data('targetArea');
        $.ajax({
            type: 'POST',
            url: 'melis/MelisCmsCategory2/MelisCmsCategoryMedia/uploadMedia',
            data: formData,
            dataType: 'json',
            processData: false,
            cache: false,
            contentType: false,
            encode: true
        }).success(function(data) {
            $(".media-upload-loader").fadeOut('medium');
        	if (data.success === true) {
        		melisHelper.zoneReload("id_meliscategory_mini_media_library","meliscategory_mini_media_library",{fileType:mediaType,targetDiv : targetArea});
			}
		});
    });

    categoryBody.on('click', ".category-image-list .removeImage", function(){
        var countImage = $(".category-image-list").children('.category-image').length;
        if (countImage > 0) {
            var parentDiv = $(this).parent().parent();
            parentDiv.fadeOut('fast', function(){
                parentDiv.remove();
			});
        }
        if (countImage === 1) {
            $(".no-image").fadeIn('fast');
		}
        initButtonScrollToTop();
	});
    categoryBody.on('click', ".category-file .remove-file", function(){
        var countImage = $(".category-file-list .list-group").children('span').length;
        if (countImage > 0) {
            var parentDiv = $(this).parent();
            parentDiv.fadeOut('fast',function(){
                parentDiv.remove();
			});
        }
        if (countImage === 1) {
            $(".no-file").fadeIn('fast');
		}
        initButtonScrollToTop();
    });
	categoryBody.on('click','#closeMedialibrary', function(){
		var parentDiv = $(this).data('targetRemoveBackdrop');
		$(parentDiv + " .back-drop").fadeOut('fast');
	});

	categoryBody.on('click', '#categoryScrollToTop' , function(){
        $("html, body").animate({scrollTop : 10}, 500);
	});
});
window.initButtonScrollToTop = function(){
    var heightContent = $("#id_meliscategory_category_tab_media").height();
    if (heightContent < 628) {
        $("#categoryScrollToTop").fadeOut();
    } else {
        $("#categoryScrollToTop").fadeIn();
    }
};
window.enableDisableAddCategoryBtn = function(action){
	var addCategory = $('.addCategory');
	if(action == 'enable'){
		addCategory.attr('disabled', false);
		addCategory.attr('title', null);
	}else if (action == 'disable'){
		addCategory.attr('disabled', true);
		addCategory.attr('title', translations.tr_meliscategory_categories_category_no_selected_catalog_category);
	}
}

window.initCmsCategoryTreeView = function(){
	
	$("body").on("click", "#categoryTreeView", function(evt){
		$("#categoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
		evt.stopPropagation();
		evt.preventDefault();
	});
	
	$('#categoryTreeView')
		.on('changed.jstree', function (e, data) {
			//enableDisableAddCategoryBtn('enable');
		})
		.on('refresh.jstree', function (e, data) {
			//enableDisableAddCategoryBtn('disable');
		})
		.on('loading.jstree', function (e, data) {
			melisCoreTool.pending("categoryTreeViewSearchInput");
			melisCoreTool.pending("categorySiteFilter");
		})
		.on('loaded.jstree', function (e, data) {
            melisCoreTool.pending("meliscategory_categories_list_search_input");
			var temp = $('ul.jstree-container-ul > li > a');
			temp.each(function(){
				var father = $(this);
				var fatherIcon = father.data('fathericon');
				var temp = father.find('i');
				father.html(temp.get(0).outerHTML + '<b>' + fatherIcon +' ' + father.text() + '</b>');
			})

		})
		.on('refresh.jstree', function (e, data) {
			var temp = $('ul.jstree-container-ul > li > a');
			temp.each(function(){
				var father = $(this);
				var fatherIcon = father.data('fathericon');
				var temp = father.find('i');
				father.html(temp.get(0).outerHTML + '<b>' + fatherIcon +' ' + father.text() + '</b>');
			});
            $("#categorySiteFilter").removeAttr('disabled');
            $("#categoryTreeViewSearchInput").removeAttr('disabled');
            $(".category-list-lang-dropdown").removeAttr('disabled');
            $(".category-list-lang-dropdown").css("cursor","default	");
            $("#clearSearchInputBtn").removeAttr('disabled');
            $("#collapseCategoryTreeViewBtn").removeAttr('disabled');
            $("#expandCategoryTreeViewBtn").removeAttr('disabled');
            $("#refreshCategoryTreeView").removeAttr('disabled');
            if ($(".jstree-container-ul").children("li").length ===  0) {
                $("#noResultData").fadeIn("fast").css("display","inline-block");
			} else {
                $("#noResultData").fadeOut("fast");
			}

		})
		.on('ready.jstree', function (e, data) {
			/*console.log(data);*/
		})
		.on('load_node.jstree', function (e, data) {
			/*console.log(data);*/
		})
		.on('open_node.jstree', function (e, data) {
			
			if(categoryOpeningItemFlag == true){
				if($(".cat-div").length){
					// if Node open sub nodes and not visible to the parent container, this will scroll down to show the sub nodes
					if($(".cat-div #"+data.node.id).offset().top + $(".cat-div #"+data.node.id).height() > $(".cat-div").offset().top + $(".cat-div").height() ){
						// exucute scroll after the opening animation of the node
						$timeOut = setTimeout(function(){ 
							var catContainer = $('.cat-div').scrollTop();
							var catItemHeight = $(".cat-div #"+data.node.id).innerHeight()
							$('.cat-div').animate({
								scrollTop: catContainer + catItemHeight
							}, 'slow');
							
						}, 1000);
					}
				}
			}
		})
		.on('after_open.jstree', function (e, data) {
			
			$.each(data.node.children_d, function(k, v){
				
				var textlang = $('#'+v+'_anchor').data('textlang');
				var products = $('#'+v+'_anchor').data('numprods');
			//	var spanHtml = '<span title="' + translations.tr_meliscategory_categories_list_tree_view_product_num + '">('+ products +')</span>';
			// 	var seoId = $('#'+v+'_anchor').data('seopage');
			// 	if(seoId){
			// 		spanHtml = spanHtml + ' - <span class="fa fa-file-o"></span> ' +  seoId ;
			// 	}
				//
				// if(textlang){
				// 	spanHtml = ' ' + textlang + spanHtml;
				// }
				
				// if(!$('#'+v+'_anchor').hasClass('updatedText')){
				// 	$('#'+v+'_anchor').append(spanHtml);
				// 	$('#'+v+'_anchor').addClass('updatedText');
				// }
				
			});
		 })
		.on("move_node.jstree", function (e, data) {
	        // Category Id
	        var categoryId = data.node.id;
	        // New category Parent ID
	        // if value is '#', the Category is on the root of the list
	        var newParentId = (data.parent=='#') ? '-1' : data.parent;
	        // Old category Parent ID
	        // if value is '#', the Category is on the root of the list
	        var oldParent = (data.old_parent=='#') ? '-1' : data.old_parent;
	        // New Category Position
	        // Position is the index on the data
	        // Adding One(1) to make to avaoid Zero(0) index of position
	        var categoryNewPosition = data.position + 1;
	        
	        var dataString = new Array();
			// get data from input
	        dataString.push({
				name: "cat_id",
				value: parseInt(categoryId, 10)
			});
			// get date data from param
			dataString.push({
				name: "cat_father_cat_id",
				value: parseInt(newParentId, 10)
			});
			// get date data from param
			dataString.push({
				name: "cat_order",
				value: categoryNewPosition
			});
			// get date data from param
			dataString.push({
				name: "old_parent",
				value: parseInt(oldParent, 10)
			});
			
			dataString = $.param(dataString);
			
	        $.ajax({
		        type        : "POST", 
		        url         : "/melis/MelisCmsCategory2/MelisCmsCategoryList/saveCategoryTreeView",
		        data		: dataString,
		        dataType    : "json",
		        encode		: true
			}).done(function(data) {
				
				if(data.success) {
					$currentCategoryId = $("#saveCategory").data("catid");
					
					if($currentCategoryId == categoryId){
						$("#saveCategory").data("catfatherid", newParentId);
					}
				}else{
					alert( translations.tr_meliscore_error_message );
				}
			}).fail(function(){
				
				alert( translations.tr_meliscore_error_message );
			});
	    })
	    .jstree({
		"contextmenu" : {
		    "items" : function (node) {
		        return {
		            "Add" : {
		                "label" : translations.tr_meliscategory_categories_common_btn_add,
		                "icon"  : "fa fa-plus",
		                "action" : function (obj) {
		                	
		                	var parentId = parseInt(node.id);
		                	var position = node.children.length + 1;
		                	
		                	$("#categoryTreeViewPanel").collapse("hide");
		                	
		                	var zoneId = "id_meliscategory_categories_category";
		                	var melisKey = "meliscategory_categories_category";
                            $("#"+zoneId).removeClass("hidden");
		            		melisHelper.zoneReload(zoneId, melisKey,{catId:0, catFatherId:parentId, catOrder:position});
		                	
		                }
		            },
		            "Update" : {
		                "label" : translations.tr_meliscategory_categories_common_btn_update,
		                "icon"  : "fa fa-edit",
		                "action" : function (obj) {
		                	
		            		var catId = parseInt(node.id , 10);
		                	
		            		var zoneId = 'id_meliscategory_categories_category';
		            		var melisKey = 'meliscategory_categories_category';
                            $("#"+zoneId).removeClass("hidden");
		            		melisHelper.zoneReload(zoneId, melisKey, {catId : catId});
		            		
		            		$("#categoryTreeViewPanel").collapse("hide");
		                	
		                }
		            },
		            "Delete" : {
		                "label" : translations.tr_meliscategory_categories_common_btn_delete,
		                "icon"  : "fa fa-trash-o",
		                "action" : function (obj) {
		                		
		                	var dataString = new Array();
		                	
		                	// New category Parent ID
		        	        // if value is '#', the Category is on the root of the list
		        	        var parentId = (node.parent=='#') ? '-1' : parseInt(node.parent, 10);
		        	        
		        	        dataString.push({
		        				name: "cat_father_cat_id",
		        				value: parentId
		        			});
		                	
		                	var cattId = parseInt(node.id);
		                	
		                	dataString.push({
		        				name: "cat_id",
		        				value: cattId
		        			});
		                	
		                	dataString = $.param(dataString);
		                	
		                	var deleteTitle = translations.tr_meliscategory_categories_category_delete;
		                	var deleteMessage = translations.tr_meliscategory_categories_category_delete_confirm_msg;
		                	if(parentId == '-1'){
		                		deleteTitle = translations.tr_meliscategory_categories_catalog_delete;
			                	deleteMessage = translations.tr_meliscategory_categories_catalog_delete_confirm_msg;
		                	}
		                	
		                	// deletion confirmation
		            		melisCoreTool.confirm(
		            		translations.tr_meliscategory_categories_common_label_yes,
		            		translations.tr_meliscategory_categories_common_label_no,
		            		deleteTitle, 
		            		deleteMessage, 
		            		function() {
		            			$.ajax({
			        		        type        : "POST", 
			        		        url         : "/melis/MelisCmsCategory2/MelisCmsCategory/deleteCategory",
			        		        data		: dataString,
			        		        dataType    : "json",
			        		        encode		: true
			        			}).done(function(data) {
			        				if(data.success) {
			        					var catTree = $('#categoryTreeView').jstree(true);
			        					catTree.delete_node(cattId+'_categoryId_anchor');
			        	            	
			        	            	if($("#saveCategory").data("catid")==cattId){
			        	            		var zoneId = "id_meliscategory_categories_category";
			    		                	var melisKey = "meliscategory_categories_category";
			    		                	
			    		            		melisHelper.zoneReload(zoneId, melisKey);
			        	            	}
			        	            	
			        	            	melisCore.flashMessenger();
			        					melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			        				}else{
			        					melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			        				}
			        			}).fail(function(){
			        				alert( translations.tr_meliscore_error_message );
			        			});
		            		});
		                }
		            },
		        };
		    }
		},
	    "core" : {
	    	"multiple": false,
	        "check_callback": true,
	        "animation" : 500,
	        "themes": {
                "name": "proton",
                "responsive": false
            },
            "dblclick_toggle" : false,
	        "data" : {
	        	"cache" : false,
	            "url" : "/melis/MelisCmsCategory2/MelisCmsCategoryList/getCategoryTreeView?langlocale="+$("#categoryTreeView").data('langlocale'),
	        },
	    },
	    "types" : {
            "#" : {
                "valid_children" : ["catalog"]
            },
            "catalog" : {
                "valid_children" : ["category"]
            },
            "category" : {
            	"valid_children" : ["category"]
            },
        },
	    "plugins": [
            "contextmenu", // plugin makes it possible to right click nodes and shows a list of configurable actions in a menu.
	        "changed", // Plugins for Change and Click Event
	        "dnd", // Plugins for Drag and Drop
	        "search", // Plugins for Search of the Node(s) of the Tree View
	        "types", // Plugins for Customizing the Nodes
        ]
    });
	
	$("body").on("click", ".categoryProductsExport", function() {
		if(!melisCoreTool.isTableEmpty("categoryProductListTbl")) {
			melisCoreTool.exportData('/melis/MelisCommerce/MelisComCategory/productsExportToCsv?catId='+$(this).data('catid'));
		}
	});
}

// Category Information Status Switch Initialization
window.initCategoryStatus = function(){
	$('#cat_status').bootstrapSwitch();
}

window.initCategoryProducts = function(data, tblSettings) {
	
	// get Category Id from table data
	var catId = $("#" + tblSettings.sTableId ).data("catid");
	
	// Add DataTable Data catId and assign value of CategoryId
	data.catId = catId;
	
	var catLangLocale = $("#categoryTreeView").data('langlocale');
	
	// Add DataTable Data catLangLocale and assign value of catLangLocale
	data.catLangLocale = catLangLocale;
	
	$('#categoryProductListTbl').on( 'row-reorder.dt', function ( e, diff, edit ) {
	    var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';

	    for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
	        var rowData = $categoryProductListTbl.row( diff[i].node ).data();
	         result += rowData[1]+' updated to be in position '+ diff[i].newData+' (was '+diff[i].oldData+')<br>';
	    }
		
	    if(!$.isEmptyObject(diff)){
	        
	        var dataString = new Array;
	        var prdNodes = new Array;
	        
	        $.each(diff, function(){
	        	prdNodes.push(this.node.id+'-'+this.newPosition);
	        });
	        
	        dataString.push({
				name : "catPrdOrderData",
				value: prdNodes.join()
			});
			
	        dataString = $.param(dataString);
			
		    $.ajax({
			     type        : "POST", 
			     url         : "/melis/MelisCommerce/MelisComCategory/reOrderCategoryProducts",
			     data		: dataString,
			     dataType    : "json",
			     encode		: true
			}).done(function(data) {
				if(!data.success) {
					alert( translations.tr_meliscore_error_message );
				}
			}).fail(function(){
				alert( translations.tr_meliscore_error_message );
			});
		}
	});
}

window.initCategoryProductsImgs = function(){
	// Lightbox Plugin Initialization
    lightbox.option({
	    'resizeDuration': 200,
	    'wrapAround': true
    })
}


