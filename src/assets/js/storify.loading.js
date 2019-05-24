var storify = storify || {};

storify.slideUp =  {
                scale: 0.5,
                opacity: null
            };

storify.loading = {
	addElementIfNotExist:function(){
		//create HTML element if not exist
		if( !$("#loading").length ){
			$("body").append(
				$("<modal>").addClass("modal fullscreen modal-loading")
							.attr({tabindex:-1, role:"dialog", id:"loading"})
							.append(
								$("<div>").addClass("d-flex justify-content-center")
            					  		  .append(
            					  			$("<i>").addClass("fa fa-spinner fa-spin")
            					  		   )
							)
			);
		}
	},
	show:function(){
		storify.loading.addElementIfNotExist();
		//show modal
		$("#loading").modal("show");
	},
	hide:function(){
		//hide modal
		$("#loading").modal("hide");
	}
};