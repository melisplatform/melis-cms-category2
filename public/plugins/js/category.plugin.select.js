window.initCategorySelectField  =  function(){
    var targetElement = $('.melis-cms-category-select');
    targetElement.wrap("<div></div>");
    var btn = "<a class='btn btn-default melis-cms-category-select-button'><i class='fa fa-sitemap'></i></a>";
    targetElement.after(btn);
};
window.initCategorySelectTree = function(targetElement){
    $(targetElement).jstree({
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
                "url" : "/melis/MelisCmsCategory2/MelisCmsCategoryList/getCategoryTreeView?langlocale="+$(targetElement).data('langlocale'),
            }
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
            "search" // Plugins for Search of the Node(s) of the Tree View
        ]
    });
    // remove all dblclick functions
    $("body").off('dblclick',".melis-cms-category-select-tree .jstree-node");
};
$(function(){
    var categorySelectBtn = '.melis-cms-category-select-button';
    var melisCmsCategorySelectField = null;
    body.on('click', categorySelectBtn, function(){
        var zoneId = "melis_cms_categories_category_select_modal_content";
        var melisKey = "melis_cms_categories_category_select_modal_content";
        var modalUrl = "/melis/MelisCmsCategory2/MelisCmsCategorySelect/render-category-select-modal";
        melisCmsCategorySelectField = $(categorySelectBtn).prev();
        var params = {};
        melisHelper.createModal(zoneId,melisKey,false,params,modalUrl,function(){

        });
    });
    //selecting a category
    body.on('click','#add-selected-category', function(){
        var selectedCategory =  $(".melis-cms-category-select-tree .jstree-clicked");
        if (selectedCategory.length > 0 ) {
            var categoryid = selectedCategory.attr('id').split('_')[0];
            melisCmsCategorySelectField.val(categoryid);
        } else {
            melisCmsCategorySelectField.val($("#root-checkbox").val());
        }
    });
    body.on('dblclick','#melis_cms_categories_category_select_modal_content .jstree-node', function(){
       console.log('i am selected');
    });
    body.on('click', '#root-checkbox', function(){
        $(".melis-cms-category-select-tree .jstree-clicked").removeClass('jstree-clicked');
    });
    body.on('click','#melis_cms_categories_category_select_modal_content .jstree-node', function(){
        $("#root-checkbox").prop("checked", false);
    });

});