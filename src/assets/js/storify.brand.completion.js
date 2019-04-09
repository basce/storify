var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.completion = {
	addElementIfNotExist:function(){
		if( !$("#completionDialog").length ){
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

			$("body").append($("<modal>").addClass("modal").attr({tabindex:-1,role:"dialog", id:"finalizeDialog"})
				.append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
					.append($("<div>").addClass("modal-content")
						.append($("<div>").addClass("modal-header")
							.append($("<h5>").addClass("modal-title").text("Close Creator Task"))
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"})
								.append($("<span>").attr({"aria-hidden":true}).html("&times;"))
							)
						)
						.append($("<div>").addClass("modal-body")
							.append($("<p>").append("Close creator's task even he/she hasn't complete all task?"))
						)
						.append($("<div>").addClass("modal-footer")
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"}).addClass("btn btn-primary small").text("Cancel"))
							.append($("<button>").addClass("btn btn-primary small").text("Confirm").click(function(e){
                                    e.preventDefault();
                                    var project_id = $(this).attr("data-project_id"),
                                    	user_id = $(this).attr("data-user_id");
                                    storify.brand.completion.completeCompletion(project_id, user_id);
                                }))
						)
					)
				)
			);	

			$("body").append($("<modal>").addClass("modal").attr({tabindex:-1,role:"dialog", id:"finalizeAllDialog"})
				.append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
					.append($("<div>").addClass("modal-content")
						.append($("<div>").addClass("modal-header")
							.append($("<h5>").addClass("modal-title").text("Close All Creator Task"))
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"})
								.append($("<span>").attr({"aria-hidden":true}).html("&times;"))
							)
						)
						.append($("<div>").addClass("modal-body")
							.append($("<p>").append("Close all creator's task even they haven't complete all task?"))
						)
						.append($("<div>").addClass("modal-footer")
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"}).addClass("btn btn-primary small").text("Cancel"))
							.append($("<button>").addClass("btn btn-primary small").text("Confirm").click(function(e){
                                    e.preventDefault();
                                    var project_id = $(this).attr("data-project_id");
                                    storify.brand.completion.completeCompletionAll(project_id);
                                }))
						)
					)
				)
			);	
		}
	},
	createCompletionItem:function(data){

		//deliverable items
		var items = $("<div>").addClass("items");

		$.each(data.completion.deliverables, function(index,value){
			var icon;
			if(value.type == "photo"){
				icon = $("<i>").addClass("fa fa-file-image-o");
			}else{
				icon = $("<i>").addClass("fa fa-file-video-o")
			}
			if(value.status == "accepted"){
				items.append($("<div>").addClass("item complete "+value.type)
									.attr({title:"done"})
						.append($("<div>").addClass("icon")
							.append(icon)
						)
						.append($("<div>").addClass("icon_name"))
					);
			}else{
				items.append($("<div>").addClass("item "+value.type)
						.append($("<div>").addClass("icon")
							.append(icon)
						)
						.append($("<div>").addClass("icon_name"))
					);
			}
		});

		//bounty
		var bounty_cont = $("<div>").addClass("bounty_cont");
		if(data.completion.bounty_type == "cash" || data.completion.bounty_type == "both"){
			bounty_cont.append($("<div>").addClass("cash").text(data.completion.cash));
		}

		if(data.completion.bounty_type == "gift" || data.completion.bounty_type == "both"){
			bounty_cont.append($("<div>").addClass("gift").text(data.completion.gift));	
		}

		//action
		var action_cont = $("<div>").addClass("text-center");
		if(data.status == "close"){
			action_cont.append($("<span>").addClass("closed").text("Closed")
				);
		}else{
			action_cont.append(
				$("<button>").addClass("btn btn-primary small").text("Close")
					.click(function(e){
						$("#finalizeDialog .modal-footer .btn:eq(1)").attr({"data-user_id":data.user_id});
						$("#finalizeDialog").modal("show");
					})
			);
		}
		
		var user_obj = storify.project.user.getUserDetail(data.user_id);

		return $("<div>").addClass("completion_item")
					.append($("<div>").addClass("user_col")
						.append($("<div>").addClass("user_profile").css({"background-image":"url("+user_obj.profile_image+")"}))
						.append($("<div>").addClass("user_name").text(user_obj.display_name))
					)
					.append($("<div>").addClass("completion_col").append(items)
								.append($("<div>").addClass("completion_summary").text("Complete :"+parseInt(data.completion.complete*100, 10)+"%"))
						)
					.append($("<div>").addClass("bounty_col").append(bounty_cont)
						)
					.append($("<div>").addClass("action_col").append(action_cont));
	},
	displayCompletion:function(data){
		storify.brand.completion.addElementIfNotExist();

		$("#completionDialog .completion_items").empty();
		var alldone = true;
		$.each(data, function(index,value){
			$("#completionDialog .completion_items").append(storify.brand.completion.createCompletionItem(value));
			if(value.status != "close"){
				alldone = false;
			}
		});
		if(alldone){
			$("#completionDialog .modal-footer .btn:eq(0)").css({display:"none"});
			$("#completionDialog .modal-footer .btn:eq(1)").removeAttr("style");
		}else{
			$("#completionDialog .modal-footer .btn:eq(0)").removeAttr("style");
			$("#completionDialog .modal-footer .btn:eq(1)").css({display:"none"});
		}
		$("#completionDialog").modal("show");
	},
	_gettingCompletion:false,
	getCompletion:function(project_id, callback){
		if(storify.brand.completion._gettingCompletion) return;
		storify.brand.completion._gettingCompletion = true;
		$.ajax({
            type:"POST",
            dataType:'json',
            data:{
                project_id:project_id,
                method:"getCompletion"
            },
            error:function(){
            	storify.brand.completion._gettingCompletion = false;
                if(callback){
                    callback();
                }
            },
            success:function(res){
            	storify.brand.completion._gettingCompletion = false;
            	storify.brand.completion.displayCompletion(res.data);
            	$("#finalizeDialog .modal-footer .btn:eq(1)").attr({"data-project_id":project_id});
            	$("#finalizeAllDialog .modal-footer .btn:eq(1)").attr({"data-project_id":project_id});
            	$("#completionDialog .modal-footer .btn").attr({"data-project_id":project_id});
                if(callback){
                    callback(res.data);
                }
            }
        });
	},
	_completingCompletion:false,
	completeCompletion:function(project_id, user_id){
		if(storify.brand.completion._completingCompletion) return;
		storify.brand.completion._completingCompletion = true;
		$.ajax({
			type:"POST",
			dataType:"json",
			data:{
				project_id:project_id,
				user_id:user_id,
				status:"close",
				method:"changeUserStatus"
			},
			error:function(){
				storify.brand.completion._completingCompletion = false;
			},
			success:function(res){
				storify.brand.completion._completingCompletion = false;
				$("#finalizeDialog").modal("hide");
				storify.brand.completion.getCompletion(project_id);
			}
		});
	},
	_completingCompletionAll:false,
	completeCompletionAll:function(project_id){
		if(storify.brand.completion._completingCompletionAll) return;
		storify.brand.completion._completingCompletionAll = true;
		$.ajax({
			type:"POST",
			dataType:"json",
			data:{
				project_id:project_id,
				method:"closeAll"
			},
			error:function(){
				storify.brand.completion._completingCompletionAll = false;
			},
			success:function(res){
				storify.brand.completion._completingCompletionAll = false;
				$("#finalizeAllDialog").modal("hide");				
				storify.brand.completion.getCompletion(project_id);
				
			}
		});
	},
	_closingProject:false,
	closeProject:function(project_id){
		if(storify.brand.completion._closingProject) return;
		storify.brand.completion._closingProject = true;
		$.ajax({
			type:"POST",
			dataType:"json",
			data:{
				project_id:project_id,
				method:"closeProject"
			},
			error:function(){
				storify.brand.completion._closingProject = false;
			},
			success:function(rs){
				storify.brand.completion._closingProject = false;
				

				window.location.href = "/user@"+rs.userid+"/projects/closed/"+rs.project_id;
				//go to close project page
			}
		})
	}
};