var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.completion_closed = {
	addElementIfNotExist:function(){
		if( !$("#finalizeDialog").length ){
			/*
			$("body").append($("<modal>").addClass("modal").attr({tabindex:-1,role:"dialog", id:"completionDialog"})
				.append($("<div>").addClass("modal-dialog modal-dialog-centered modal-xl").attr({role:"document"})
					.append($("<div>").addClass("modal-content")
						.append($("<div>").addClass("modal-header")
							.append($("<h5>").addClass("modal-title").text("Completion"))
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"})
								.append($("<span>").attr({"aria-hidden":true}).html("&times;"))
							)
						)
						.append($("<div>").addClass("modal-body")
							.append($("<div>").addClass("table_top")
								.append($("<div>").addClass("user_col").text("Creator"))
								.append($("<div>").addClass("completion_col").text("Deliverables"))
								.append($("<div>").addClass("bounty_col").text("Payment Amount"))
								.append($("<div>").addClass("action_col").text("Action / Status"))
							)
							.append($("<div>").addClass("completion_items"))
						)
						.append($("<div>").addClass("modal-footer")
							.append($("<button>").addClass("btn btn-primary small").text("Close All").click(function(e){
                                    e.preventDefault();
                                    $("#finalizeAllDialog").modal("show");
                                }))
							.append($("<button>").addClass("btn btn-primary small").text("Close Project").click(function(e){
                                    e.preventDefault();
                                    var project_id = $(this).attr("data-project_id");
                                    storify.brand.completion.closeProject(project_id);
                                }))
						)
					)
				)
			);
			*/
		}
	},
	createCompletionItem:function(data){

		//deliverable items
		var items = $("<div>").addClass("items");

		var photo_count = parseInt(data.completion.total_photo, 10),
			video_count = parseInt(data.completion.total_video, 10);

		$.each(data.completion.deliverables, function(index,value){
			var icon;
			if(value.type == "photo"){
				icon = $("<i>").addClass("fa fa-camera");
				photo_count--;
			}else{
				icon = $("<i>").addClass("fa fa-video-camera");
				video_count--;
			}
			if(value.status == "accepted"){
				items.append($("<div>").addClass("item complete "+value.type)
							.attr({title:"approved"})
						.append($("<div>").addClass("icon")
							.append(icon)
						)
					);
			}else if(value.status == "rejected"){
				items.append($("<div>").addClass("item rejected "+value.type)
							.attr({title:"rejected"})
						.append($("<div>").addClass("icon")
							.append(icon)
						)
					);
			}else{
				items.append($("<div>").addClass("item pending "+value.type)
							.attr({title:"pending"})
						.append($("<div>").addClass("icon")
							.append(icon)
						)
					);
			}
		});

		if(photo_count){
			while(photo_count-- > 0){
				items.append($("<div>").addClass("item photo")
							.attr({title:"empty"})
						.append($("<div>").addClass("icon")
							.append($("<i>").addClass("fa fa-camera"))
						)
					);
			}
		}

		if(video_count){
			while(video_count-- > 0){
				items.append($("<div>").addClass("item video")
						.append($("<div>").addClass("icon")
							.append($("<i>").addClass("fa fa-video-camera"))
						)
					);	
			}
		}

		//bounty
		var bounty_cont = $("<h2>").addClass("bounty_cont");
		if(data.completion.bounty_type == "cash"){
			bounty_cont.text("S$"+storify.project.formatMoney(data.completion.cash));
		}else if(data.completion.bounty_type == "both"){
			bounty_cont.text("S$"+storify.project.formatMoney(data.completion.cash)+" & ")
					.append($("<i>").addClass("fa fa-gift"));
		}else{
			bounty_cont.append($("<i>").addClass("fa fa-gift"));
		}

		//action
		var action_cont = $("<div>").addClass("text-right");
		if(data.status == "close"){
			action_cont.append($("<span>").addClass("closed").text("Paid")
				);
		}else{
			action_cont.append($("<span>").addClass("closed").text("Unpaid")
				);
		}
		
		var user_obj = storify.project.user.getUserDetail(data.user_id);

		return $("<div>").addClass("completion_item")
					.append($("<div>").addClass("user_col")
						.append($("<div>").addClass("user_profile").css({"background-image":"url("+user_obj.profile_image+")"}).attr({title:user_obj.display_name}))
					)
					.append($("<div>").addClass("perc_col")
						.append($("<h2>").text(parseInt(data.completion.complete*100, 10)+"%"))
					)
					.append($("<div>").addClass("completion_col").append(items))
					.append($("<div>").addClass("bounty_col").append(bounty_cont)
						)
					.append($("<div>").addClass("action_col").append(action_cont));
	},
	displayCompletion:function(data){
		storify.brand.completion_closed.addElementIfNotExist();

		$("#final-content").empty();
		var alldone = true;
		$.each(data, function(index,value){
			$("#final-content").append(storify.brand.completion_closed.createCompletionItem(value));
			if(value.status != "close"){
				alldone = false;
			}
		});

		if(data && data.length){
			
		}else{
			$("#final-content").append($("<div>").append($("<p>").text("No Submissions made yet.")));
		}
	},
	_gettingCompletion:false,
	getCompletion:function(project_id, callback){
		if(storify.brand.completion_closed._gettingCompletion) return;
		storify.brand.completion_closed._gettingCompletion = true;
		$.ajax({
            type:"POST",
            dataType:'json',
            data:{
                project_id:project_id,
                method:"getCompletion"
            },
            error:function(){
            	storify.brand.completion_closed._gettingCompletion = false;
                if(callback){
                    callback();
                }
            },
            success:function(res){
            	storify.brand.completion_closed._gettingCompletion = false;
            	storify.brand.completion_closed.displayCompletion(res.data);
            	if(callback){
                    callback(res.data);
                }
            }
        });
	}
};