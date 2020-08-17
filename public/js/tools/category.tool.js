/* Category Sticky Top Start */
if( melisCore.screenSize >= 768){
	$(window).on('scroll click resize', function(e) {
		$("#id_meliscategory_categories_category_header").css("width","100%");
		var stickyCatNav = $("#"+ activeTabId + ' #id_meliscategory_categories_category');
		
			if ( stickyCatNav.length ) {
				var position = stickyCatNav.position();
					if (position.top < ($(window).scrollTop() - 10)) {
						$("#id_meliscategory_categories_category").addClass("fix-cat");
						//$("#cmsCategoryInfoPanel").css("padding-top","66px");
						$("#cmsSaveCategory").css("margin-top","10px");
						$("#id_meliscategory_categories_category_header").width($("#id_meliscategory_categories_list").width());
					}
					else {
						$("#id_meliscategory_categories_category").removeClass("fix-cat");
						$("#cmsCategoryInfoPanel").css("padding-top","0");
						$("#cmsSaveCategory").css("margin-top","0");
					}
			}		
	});
}
/* Category Sticky Top End */
var categoryOpeningItemFlag = true;

$(function() {
	var $categoryBody = $("body");

		$categoryBody.on("click", "#id_meliscms_categories_list_header_add_category", function(e) {
			$("#cmsCategoryTreeViewPanel").collapse("hide");
			var zoneId 			= 'id_meliscategory_categories_category',
				melisKey 		= 'meliscategory_categories_category',
				catTree 		= $('#cmsCategoryTreeView').jstree(true),
				catSelected 	= catTree.get_selected(),
				catFatherId 	= '',
				catSiteSelected = $("#categorySiteFilter").val(),
				currentLocale 	= $(".category-list-lang-dropdown").data('locale');

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

				melisHelper.zoneReload(zoneId, melisKey, {
					catId : 0,
					catFatherId: catFatherId,
					selectedSiteId : catSiteSelected,
					currentLocale : currentLocale,
					forAdding : true
				});
		});
		
		$categoryBody.on("click", "#cmsSaveCategory", function() {
			var $this 		= $(this),
				catId 		= $this.data('catid'),
				dataString 	= new Array;

				$this.button("loading");

				// Serialize Forms of Category Panel
				dataString = $("#id_meliscategory_categories_category form").not(".cat_trans_form").serializeArray();

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

					if($('input[name="cms_cat_status"]').is(':checked')){
						catStatus = 1;
					}
					
					dataString.push({
						name : "cms_cat_status",
						value: catStatus
					});
					// save media
					//image
					$("#id_meliscategory_category_tab_media_content_left").find('input').each(function(index) {
						dataString.push({
							name: "cat2_media_image["+ index + "]",
							order : index,
							value : $(this).val()
						});
					});

					$("#id_meliscategory_category_tab_media_content_right").find('input').each(function(index) {
						dataString.push({
							name: "cat2_media_file["+ index+"]",
							order: index,
							value : $(this).val()
						});
					});

				// Category Transalations
				$("#id_meliscategory_categories_category_form_transalations form.cat_trans_form").each(function() {
					var $this = $(this);

						langLocale = $this.data("locale");
						langId = $this.data("langid");
						
						// convert the serialized form values into an array
						catDataString = $this.serializeArray();
						
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
					
					$("#cmsSaveCategory").button("reset");
					
					if(data.success) {
						$("#cmsCategoryTreeViewPanel").collapse("show");
						
						$("body").animate({
							scrollTop: 0
						}, 1000); 
						
						melisCore.flashMessenger();
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
						
						var catTree 		= $('#cmsCategoryTreeView').jstree(true),
							// Get Current Url of the category Tree view
							realUrl 		= catTree.settings.core.data.url,
							// selected Category Id/Node
							selectedNode 	= '';

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
							
						}
						else {
							// Category exist
							var nodeData 	= catTree.get_node(catId),
								nodeParents = new Array;
							
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
						$("#cmsCategoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
						//refresh Category view
						catTree.refresh();
						// Rollback the real/default url
						catTree.settings.core.data.url = realUrl;
						
						var zoneId 		= 'id_meliscategory_categories_category',
							melisKey 	= 'meliscategory_categories_category';

							melisHelper.zoneReload(zoneId, melisKey, {catId : data.id});
						
							// Highlighting the node
							$("#cmsCategoryTreeView #"+selectedNode+" div").first().addClass("jstree-wholerow-clicked");
					}
					else {
						var customErrorSite = data.customError.site;

							if (customErrorSite === 1) {
								$('.category-sites-heading').css('color',"#ff0000");
							}
							else {
								$('.category-sites-heading').removeAttr('style')
							}

						var customErrorTrans = data.customError.trans;

							if (customErrorTrans === 1) {
								$("form input[name='catt2_name']").prev().addClass('text-red');
							}
							else {
								$("form input[name='catt2_name']").prev().removeClass('text-red');
							}

						var customErrorDates = data.customError.datesValidation;

							if (customErrorDates === 1) {
								$("input[name='cat_date_valid_start']").parent().prev().addClass('text-red');
								$("input[name='cat_date_valid_end']").parent().prev().addClass('text-red');
							}
							else {
								$("input[name='cat_date_valid_start']").parent().prev().removeClass('text-red');
								$("input[name='cat_date_valid_end']").parent().prev().removeClass('text-red');
							}

							melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
							melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscategory_categories_category_form_transalations");
					}
					
					melisCore.flashMessenger();
					
				}).fail(function() {
					$("#cmsSaveCategory").button("reset");					
					alert( translations.tr_meliscore_error_message);
				});
		});
		
		// Category Tree Languages Dropdown
		$categoryBody.on("click", ".category-tree-view-lang.cms-category2-tree-view-lang li a", function() {
			categoryOpeningItemFlag = false;

			var $this 		= $(this),
				langText 	= $this.text(),
				langLocale 	= $this.data('locale');

				$('.cat-tree-view-languages.cms-category2-tree-view-languages span.filter-key').text(langText);
				// disable buttons
				$("#cmsCategoryTreeViewSearchInput").attr("disabled","disabeld");
				$(".category-list-lang-dropdown").attr("disabled","disabeld");
				$(".category-list-lang-dropdown").css("cursor","not-allowed");
				$("#clearSearchInputBtn").attr("disabled","disabeld");
				$("#collapseCategoryTreeViewBtn").attr("disabled","disabeld");
				$("#expandCategoryTreeViewBtn").attr("disabled","disabeld");
				$("#refreshCmsCategoryTreeView").attr("disabled","disabeld");

			var categorySiteFilter = $("#categorySiteFilter");

				categorySiteFilter.attr("disabled","disabeld");
				//put attribute on button add category
				$(".category-list-lang-dropdown").attr('data-locale',langLocale);

				$("#cmsCategoryTreeView").data('langlocale',langLocale);
				$("#cmsCategoryTreeView").jstree(true).settings.core.data.data = [{name : "langlocale", value: langLocale}, {name:"siteId", value : categorySiteFilter.val()}];
				$("#cmsCategoryTreeView").jstree(true).refresh();
		});

		$categoryBody.on('change',"#categorySiteFilter", function() {
			var $this 			= $(this),
				value 			= this.value,
				cmsCategoryTree = $("#cmsCategoryTreeView"),
				langLocale 		= cmsCategoryTree.data('langlocale');

				if (typeof(cmsCategoryTree.jstree(true).settings) !== "undefined" ) {
					$this.attr("disabled","disabeld");
					// disable buttons
					$("#cmsCategoryTreeViewSearchInput").attr("disabled","disabeld");
					$(".category-list-lang-dropdown").attr("disabled","disabeld");
					$(".category-list-lang-dropdown").css("cursor","not-allowed");
					$("#clearSearchInputBtn").attr("disabled","disabeld");
					$("#collapseCategoryTreeViewBtn").attr("disabled","disabeld");
					$("#expandCategoryTreeViewBtn").attr("disabled","disabeld");
					$("#refreshCmsCategoryTreeView").attr("disabled","disabeld");
					
					if (value !== "") {
						$(".info-site-filter").fadeIn('medium');
					} else {
						$(".info-site-filter").fadeOut('medium');
					}
					cmsCategoryTree.jstree(true).settings.core.data.data = [{name : "langlocale", value: langLocale},{name:"siteId", value : value}];
					cmsCategoryTree.jstree(true).refresh();
				}
		});

		// Search Input
		$categoryBody.on("keyup", "#cmsCategoryTreeViewSearchInput", function(e) { 
			categoryOpeningItemFlag = false;

			var $this 			= $(this),
				searchString 	= $this.val().trim(),
				searchResult 	= $('#cmsCategoryTreeView').jstree('search', searchString);
			
				setTimeout(function(){ 
					if($(searchResult).find('.jstree-search').length == 0 && searchString != ''){
						$("#searchNoResult").removeClass('hidden');
						$("#searchNoResult").find("strong").text(searchString);
					}else{
						$("#searchNoResult").addClass('hidden');
					}
				}, 5);
		});

		$categoryBody.on("keydown", "#cmsCategoryTreeViewSearchInput", function(e) {
			categoryOpeningItemFlag = false;

			var $this 			= $(this),
				searchString 	= $this.val().trim(),
				searchResult 	= $('#cmsCategoryTreeView').jstree('search', searchString);

				setTimeout(function(){
					if($(searchResult).find('.jstree-search').length == 0 && searchString != ''){
						$("#searchNoResult").removeClass('hidden');
						$("#searchNoResult").find("strong").text(searchString);
					}else{
						$("#searchNoResult").addClass('hidden');
					}
				}, 5);
		});
		
		$categoryBody.on('keyup keypress', '#cmsCategoryTreeViewSearchForm', function(e) {
			var keyCode = e.keyCode || e.which;

				if (keyCode === 13) { 
					e.preventDefault();
					return false;
				}
		});
		
		// Clear Input Search
		$categoryBody.on("click", "#clearSearchInputBtn", function(e) { 
			categoryOpeningItemFlag = false;

			var catTree = $('#cmsCategoryTreeView').jstree(true);

				$("#cmsCategoryTreeViewSearchInput").val("");
				$('#cmsCategoryTreeView').jstree('search', '');
				$("#searchNoResult").addClass('hidden');
		});
		
		// Toggle Buttons for Category Tree View
		$categoryBody.on("click", "#expandCategoryTreeViewBtn", function(e) { 
			categoryOpeningItemFlag = false;
			$("#cmsCategoryTreeView").jstree("open_all");
		});

		$categoryBody.on("click", "#collapseCategoryTreeViewBtn", function(e) { 
			categoryOpeningItemFlag = false;
			$("#cmsCategoryTreeView").jstree("close_all");
		});
		
		// Refrech Category Tree View
		$categoryBody.on("click", "#refreshCmsCategoryTreeView", function(e) { 
			categoryOpeningItemFlag = false;

			var catTree = $('#cmsCategoryTreeView').jstree(true);

				catTree.deselect_all();
				catTree.refresh();
				$("#cmsCategoryTreeViewSearchInput").val("");
				$('#cmsCategoryTreeView').jstree('search', '');
				$("#searchNoResult").addClass('hidden');
		});
		
		// Category Information Form Countries Custom Checkboxes
		$categoryBody.on("click", ".cms-category-checkbox", function(evt) {
			var $this = $(this);

				if($this.find('.fa').hasClass('fa-check-square-o')) { // unchecking category Checkbox
					$this.find('.fa').removeClass('fa-check-square-o');
					$this.find('.fa').addClass('fa-square-o');
					$this.find('input[type="checkbox"]').removeAttr('checked');
					
					// If the uncheck is check all checkbox
					if($this.find('.check-all').hasClass('fa-square-o')) {
						$(".cms-category-checkbox .fa").not(".check-all").addClass('fa-square-o');
						$(".cms-category-checkbox .fa").not(".check-all").removeClass('fa-check-square-o');
						$(".cms-category-checkbox .fa").not(".check-all").next('input[type="checkbox"]').removeAttr('checked');
					}
				}
				else { // Checking Category Checkboxes
					$this.find('.fa').removeClass('fa-square-o');
					$this.find('.fa').addClass('fa-check-square-o');
					$this.find('input[type="checkbox"]').attr('checked','checked');
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
				}
				else {
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
		$categoryBody.on("switch-change", "#cms_cat_status", function(event, state) {
			var $this = $(this);

				if(state.value == true){
					$this.find('input[type="checkbox"]').attr('checked','checked');
				}
				else {
					$this.find('input[type="checkbox"]').removeAttr('checked');
				}
		});

		// Category Tree Double Click Item Action, [#cmsCategoryTreeView .cms-tree-node]
		$categoryBody.on("dblclick", "#cmsCategoryTreeViewPanel .cms-tree-node", function(evt) {
			$("#cmsCategoryTreeViewPanel").collapse("hide");
			var $this 		= $(this),
				catId 		= parseInt($this.attr("id"), 10),
				zoneId 		= 'id_meliscategory_categories_category',
				melisKey 	= 'meliscategory_categories_category';

				if ($("#"+zoneId).length > 0) {
					melisHelper.zoneReload(zoneId, melisKey, {catId : catId , forEditing : true});
					$("#"+zoneId).removeClass("hidden");
				}

				// Highlighting the node
				$("#cmsCategoryTreeView #"+catId+" div").first().addClass("jstree-wholerow-clicked");
				// $(this).off('dblclick');
			evt.stopPropagation();
			evt.preventDefault();
		});

		// Open Single Node in JSTree
		$categoryBody.on("click", ".cat-div .jstree-node .jstree-icon", function() {
			categoryOpeningItemFlag = true;
		});
		
		$categoryBody.on("mouseenter mouseout", ".toolTipCatHoverEvent", function(e) {
			$(".thClassColId").attr("style", "");

			var $this 		= $(this),
				productId 	= $this.data("productid"),
				loaderText 	= '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';

				$.each($("table#catProductTable"+productId + " thead").nextAll(), function(i,v) {
					$(v).remove();
				});

				$(loaderText).insertAfter("table#catProductTable"+productId + " thead");
				
				var xhr = $.ajax({
					type        : 'POST', 
					url         : 'melis/MelisCommerce/MelisComProductList/getToolTip',
					data		: {productId : productId},
					dataType    : 'json',
					encode		: true
				}).done(function(data){
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

				}).fail(function() {
					alert( translations.tr_meliscore_error_message );
				});

				if(e.type === "mouseout") {
					xhr.abort();
				}
		});

		// add image
		$categoryBody.on('click', ".category-add-image" , function() {
			var catv2ImageZoneId   	= "id_meliscategory_mini_media_library",
				catv2ImageMelisKey 	= "meliscategory_mini_media_library",
				categoryv2ModalUrl 	= '/melis/MelisCmsCategory2/MelisCmsCategoryMedia/render-mini-media-modal-container',
				element 			= $(this),
				data 				= element.data();

				melisCoreTool.pending(element);
				melisHelper.createModal(catv2ImageZoneId,catv2ImageMelisKey,true,{
					fileType  		: data.type,
					targetDiv 		: ".category-image-list",
					currentPosition : data.currentposition,
					catId 			: data.catId
				},categoryv2ModalUrl, function() {
					$(".category-add-image").removeAttr('disabled ');
					melisCoreTool.done(element);
				});
		});

		$categoryBody.on('click', ".category-add-file" , function() {
			var catv2ImageZoneId   	= "id_meliscategory_mini_media_library",
				catv2ImageMelisKey 	= "meliscategory_mini_media_library",
				categoryv2ModalUrl 	= '/melis/MelisCmsCategory2/MelisCmsCategoryMedia/render-mini-media-modal-container',
				element 			= $(this),
				data 				= element.data();
			
				melisCoreTool.pending(element);
				melisHelper.createModal(catv2ImageZoneId,catv2ImageMelisKey,true,{
					fileType  		: data.type,
					targetDiv 		: ".category-file-list .list-group",
					currentPosition : data.currentposition
				},categoryv2ModalUrl, function(){
					$(".category-add-file").removeAttr('disabled ');
					melisCoreTool.pending(element);
				});
		});
		
		$categoryBody.on('click', '#cmsSaveCategoryMedia', function() {
			$("#id_meliscategory_media_upload_form").trigger('submit');
			melisCoreTool.pending(this);
		});

		$categoryBody.on('change','.upload-category-media-image', function() {
			var input = this;

				if ( input.files && input.files[0] ) {
					var reader = new FileReader();
						reader.onload = function (e) {
							$('.categoryImgDocThumbnail').attr('src', e.target.result);
						};
						reader.readAsDataURL(input.files[0]);
				}
		});

		$categoryBody.on('submit',"#id_meliscategory_media_upload_form",function(e) {
			e.preventDefault();

			var formData 		= new FormData(this),
				fileUploadValue = $("#id_meliscategory_media_upload_form input[type='file']").val(),
				saveCategoryBtn = $("#cmsSaveCategoryMedia"),
				fileType 		= saveCategoryBtn.data('fileType'),
				categoryId 		= $(".category-add-image").data('catId');

				// append categoryId
				formData.append('catId',categoryId);
				// append file Type
				formData.append('fileType',fileType);

				if (fileUploadValue !== "") {
					$.ajax({
						type		: 'POST',
						url			: '/melis/MelisCmsCategory2/MelisCmsCategoryMedia/uploadMedia',
						data		: formData,
						dataType	: 'json',
						processData	: false,
						cache		: false,
						contentType	: false,
						encode		: true,
					}).done(function(data) {
						if (data.success === true) {
							$("#closeMedialibrary").trigger('click');
							var tmpZoneId 	= 'id_meliscategory_category_tab_media_content_right_file',
								tmpMeliskey = 'meliscategory_category_tab_media_content_right_file';
								if (fileType === 'image') {
									tmpZoneId   = 'id_meliscategory_category_tab_media_content_left_image_list';
									tmpMeliskey = 'meliscategory_category_tab_media_content_left_image_list';
								}
								melisHelper.zoneReload(tmpZoneId, tmpMeliskey,{ catId:data.id });

								if (data.tmpUpload === false) {
									melisHelper.melisOkNotification(data.textTitle, data.textMessage);
									melisCore.flashMessenger();
								}
						}
						else {
							melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
							saveCategoryBtn.removeAttr('disabled');
						}
					}).fail(function() {
						saveCategoryBtn.removeAttr('disabled');
						alert( translations.tr_meliscore_error_message );
					})
				} else {
					saveCategoryBtn.removeAttr('disabled');

					var message = "Please upload a file",
						heading = translations.tr_melis_cms_category_v2;
					
						melisHelper.melisKoNotification(heading,message);
				}
		});

		$categoryBody.on('click','#closeMedialibrary', function() {
			var $this 		= $(this),
				parentDiv 	= $this.data('targetRemoveBackdrop');

				$(parentDiv + " .back-drop").fadeOut('fast');
				$("body").css("overflow", "auto");
		});

		$categoryBody.on('click', '#categoryScrollToTop' , function() {
			$("html, body").animate({scrollTop : 10}, 500);
		});

		//Hook up ajax call
		$.ajaxPrefilter("json script", function( options , originalOptions ,jqXHR ) {
			//specifiy which ajax you want to call
			if(options.url === '/melis/MelisCore/ToolUser/updateUser'){
				jqXHR.done(function() {
					var categoryInterface = $("#id_melis_cms_categories_v2").length;
						if (categoryInterface > 0){
							// reload the category interface whenever the user update his/her info or rights
							melisHelper.zoneReload('id_melis_cms_categories_v2','melis_cms_categories_v2');
						}
				});
			}
		});
		
		$categoryBody.on('click', '#id_meliscms_catergories_list_categories_tree' , function() {
			$('#cmsCategoryTreeView').jstree("deselect_all");
		});
});

window.initButtonScrollToTop = function() {
	var heightContent = $("#id_meliscategory_category_tab_media").height();
	
		if ( heightContent < 628 ) {
			$("#categoryScrollToTop").fadeOut();
		}
		else {
			$("#categoryScrollToTop").fadeIn();
		}
};

window.enableDisableAddCategoryBtn = function(action) {
	var addCmsCategory = $('#id_meliscms_categories_list_header_add_category');
		if ( action == 'enable' ) {
			addCmsCategory.attr('disabled', false);
			addCmsCategory.attr('title', null);
		} else if ( action == 'disable' ) {
			addCmsCategory.attr('disabled', true);
			addCmsCategory.attr('title', translations.tr_meliscategory_categories_category_no_selected_catalog_category);
		}
};

window.initCmsCategoryTreeView = function() {
	var $body = $("body");

		$body.on("click", "#cmsCategoryTreeView", function(evt) {
			$("#cmsCategoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
			evt.stopPropagation();
			evt.preventDefault();
		});
		
		$('#cmsCategoryTreeView')
			.on('#cmsCategoryTreeView changed.jstree', function (e, data) {
				//enableDisableAddCategoryBtn('enable');
			})
			.on('#cmsCategoryTreeView refresh.jstree', function (e, data) {
				//enableDisableAddCategoryBtn('disable');
			})
			.on('#cmsCategoryTreeView loading.jstree', function (e, data) {
				melisCoreTool.pending("cmsCategoryTreeViewSearchInput");
				melisCoreTool.pending("categorySiteFilter");
			})
			.on('#cmsCategoryTreeView loaded.jstree', function (e, data) {
				melisCoreTool.pending("meliscategory_categories_list_search_input");
				var temp = $('#cmsCategoryTreeView ul.jstree-container-ul > li > a');

					temp.each(function() {
						var father 		= $(this),
							fatherIcon 	= father.data('fathericon'),
							temp 		= father.find('i');

							father.html(temp.get(0).outerHTML + fatherIcon +' ' + father.text() );
					});
			})
			.on('#cmsCategoryTreeView refresh.jstree', function (e, data) {
				var temp = $('#cmsCategoryTreeView ul.jstree-container-ul > li > a');

					temp.each(function() {
						var father 		= $(this),
							fatherIcon 	= father.data('fathericon'),
							temp 		= father.find('i');

							father.html(temp.get(0).outerHTML  + fatherIcon +' ' + father.text() );
					});
					
					$("#categorySiteFilter").removeAttr('disabled');
					$("#cmsCategoryTreeViewSearchInput").removeAttr('disabled');
					$(".category-list-lang-dropdown").removeAttr('disabled');
					$(".category-list-lang-dropdown").css("cursor","pointer");
					$("#clearSearchInputBtn").removeAttr('disabled');
					$("#collapseCategoryTreeViewBtn").removeAttr('disabled');
					$("#expandCategoryTreeViewBtn").removeAttr('disabled');
					$("#refreshCmsCategoryTreeView").removeAttr('disabled');

					if ($(".jstree-container-ul").children("li").length ===  0) {
						$("#noResultData").fadeIn("fast").css("display","inline-block");
					} else {
						$("#noResultData").fadeOut("fast");
					}
					$("#cmsCategoryTreeViewSearchInput").trigger('keydown');
			})
			.on('ready.jstree', function (e, data) {
				/*console.log(data);*/
			})
			.on('load_node.jstree', function (e, data) {
				/*console.log(data);*/
			})
			.on('#cmsCategoryTreeView open_node.jstree', function (e, data) {
				if(categoryOpeningItemFlag == true){
					if($(".cat-div").length){
						// if Node open sub nodes and not visible to the parent container, this will scroll down to show the sub nodes
						if($("#cmsCategoryTreeView .cat-div #"+data.node.id).offset().top + $("#cmsCategoryTreeView .cat-div #"+data.node.id).height() > $("#cmsCategoryTreeView .cat-div").offset().top + $("#cmsCategoryTreeView .cat-div").height() ){
							// exucute scroll after the opening animation of the node
							$timeOut = setTimeout(function() { 
								var catContainer 	= $('#cmsCategoryTreeView .cat-div').scrollTop(),
									catItemHeight 	= $(".cat-div #"+data.node.id).innerHeight();
								
									$('#cmsCategoryTreeView .cat-div').animate({
										scrollTop: catContainer + catItemHeight
									}, 'slow');
							}, 1000);
						}
					}
				}
			})
			.on('#cmsCategoryTreeView after_open.jstree', function (e, data) {
				$.each(data.node.children_d, function(k, v){
					var textlang = $('#cmsCategoryTreeView #'+v+'_anchor').data('textlang'),
						products = $('#cmsCategoryTreeView #'+v+'_anchor').data('numprods'),
						spanHtml = null;

						/* var seoId = $('#'+v+'_anchor').data('seopage');
						if(seoId){
							spanHtml = spanHtml + ' - <span class="fa fa-file-o"></span> ' +  seoId ;
						} */

						//
						if(textlang){
							spanHtml = ' ' + textlang ;
						}
						
						if(!$('#cmsCategoryTreeView #'+v+'_anchor').hasClass('updatedText')){
							$('#cmsCategoryTreeView #'+v+'_anchor').append(spanHtml);
							$('#cmsCategoryTreeView #'+v+'_anchor').addClass('updatedText');
						}
				});
			})
			.on("#cmsCategoryTreeView move_node.jstree", function (e, data) {
				// Category Id
				var categoryId 			= data.node.id,
					// New category Parent ID
					// if value is '#', the Category is on the root of the list
					newParentId 		= (data.parent=='#') ? '-1' : data.parent,
					// Old category Parent ID
					// if value is '#', the Category is on the root of the list
					oldParent 			= (data.old_parent=='#') ? '-1' : data.old_parent,
					// New Category Position
					// Position is the index on the data
					// Adding One(1) to make to avaoid Zero(0) index of position
					categoryNewPosition = data.position + 1,
					dataString 			= new Array();

					// get data from input
					dataString.push({
						name: "cat2_id",
						value: parseInt(categoryId, 10)
					});

					// get date data from param
					dataString.push({
						name: "cat2_father_cat_id",
						value: parseInt(newParentId, 10)
					});

					// get date data from param
					dataString.push({
						name: "cat2_order",
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
							$currentCategoryId = $("#cmsSaveCategory").data("catid");
							
							if($currentCategoryId == categoryId){
								$("#cmsSaveCategory").data("catfatherid", newParentId);
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

								var parentId = parseInt(node.id),
									position = node.children.length + 1;

									$("#cmsCategoryTreeViewPanel").collapse("hide");

									var zoneId 			= "id_meliscategory_categories_category",
										melisKey 		= "meliscategory_categories_category",
										catSiteSelected = $("#categorySiteFilter").val();

										$("#"+zoneId).removeClass("hidden");

										melisHelper.zoneReload(zoneId, melisKey,{catId:0, catFatherId:parentId, catOrder:position, forAdding : true,selectedSiteId : catSiteSelected});
							}
						},
						"Update" : {
							"label" : translations.tr_meliscategory_categories_common_btn_update,
							"icon"  : "fa fa-edit",
							"action" : function (obj) {
								var catId 		= parseInt(node.id , 10),
									zoneId 		= 'id_meliscategory_categories_category',
									melisKey 	= 'meliscategory_categories_category';

									$("#"+zoneId).removeClass("hidden");

									melisHelper.zoneReload(zoneId, melisKey, {catId : catId, forEditing : true});

									$("#cmsCategoryTreeViewPanel").collapse("hide");
							}
						},
						"Delete" : {
							"label" : translations.tr_meliscore_common_delete,
							"icon"  : "fa fa-trash-o",
							"action" : function (obj) {
								var dataString 			= new Array(),
									// New category Parent ID
									// if value is '#', the Category is on the root of the list
									parentId 			= (node.parent=='#') ? '-1' : parseInt(node.parent, 10),
									originalParentId 	= "";

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

									var deleteTitle 	= translations.tr_meliscore_common_delete,
										deleteMessage 	= translations.tr_meliscms_categories_delete_category_msg;

										// deletion confirmation
										melisCoreTool.confirm(
										translations.tr_meliscore_common_yes,
										translations.tr_meliscore_common_no,
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
												if ( data.success ) {
													var catTree = $('#cmsCategoryTreeView').jstree(true);
														catTree.delete_node(cattId+'_categoryId_anchor');

													if( $("#cmsSaveCategory").data("catid")==cattId ) {
														var zoneId 		= "id_meliscategory_categories_category",
															melisKey 	= "meliscategory_categories_category";

															melisHelper.zoneReload(zoneId, melisKey);
													}

													melisCore.flashMessenger();
													melisHelper.melisOkNotification(data.textTitle, data.textMessage);
												}
												else {
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
					"url" : "/melis/MelisCmsCategory2/MelisCmsCategoryList/getCategoryTreeView?langlocale="+$("#cmsCategoryTreeView").data('langlocale'),
				},
			},
			"types" : {
				"#" : {
					"valid_children" : ["category"]
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
};

// Category Information Status Switch Initialization
window.initCmsCategoryStatus = function() {
	$('#cms_cat_status').bootstrapSwitch();
};