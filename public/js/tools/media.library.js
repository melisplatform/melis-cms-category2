var mediaDirectory = {
    browse: function(modalUrl,zoneId,melisKey,params, targetDiv){
        melisHelper.createModal(zoneId,melisKey,false,params,modalUrl, function(){
            if ($(targetDiv).length > 0) {
                console.log('pde addan image diri');
            }
        })
    }
};
