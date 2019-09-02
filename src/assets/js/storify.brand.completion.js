var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.completion = {
	addElementIfNotExist:function(){
		if( !$("#finalizeDialog").length ){
			var div;
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
			/*
			$("body").append($("<modal>").addClass("modal").attr({tabindex:-1,role:"dialog", id:"finalizeDialog"})
				.append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
					.append($("<div>").addClass("modal-content")
						.append($("<div>").addClass("modal-header")
							.append($("<h5>").addClass("modal-title").text("Pay for submission"))
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"})
								.append($("<span>").attr({"aria-hidden":true}).html("&times;"))
							)
						)
						.append($("<div>").addClass("modal-body")
							.append($("<p>").append("This will confirm this submission and we will arrange to pay the Creator."))
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
							.append($("<h5>").addClass("modal-title").text("Pay for all submissions"))
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"})
								.append($("<span>").attr({"aria-hidden":true}).html("&times;"))
							)
						)
						.append($("<div>").addClass("modal-body")
							.append($("<p>").append("This will confirm all submissions and we will arrange to pay all Creators."))
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

			$("body").append($("<modal>").addClass("modal").attr({tabindex:-1,role:"dialog", id:"closeProjectDialog"})
				.append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
					.append($("<div>").addClass("modal-content")
						.append($("<div>").addClass("modal-header")
							.append($("<h5>").addClass("modal-title").text("Close project"))
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"})
								.append($("<span>").attr({"aria-hidden":true}).html("&times;"))
							)
						)
						.append($("<div>").addClass("modal-body")
							.append($("<p>").append("This project is done. We will close it now."))
						)
						.append($("<div>").addClass("modal-footer")
							.append($("<button>").attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"}).addClass("btn btn-primary small").text("Cancel"))
							.append($("<button>").addClass("btn btn-primary small").text("Confirm").click(function(e){
                                    e.preventDefault();
                                    var project_id = $(this).attr("data-project_id");
                                    storify.brand.completion.closeProject(project_id);
                                }))
						)
					)
				)
			);
			*/

			div = $(storify.template.simpleModal(
				{
					titlehtml:`Pay for submission`,
					bodyhtml:`<p>This will confirm this submission and we will arrange to pay the Creator.</p>`	
				},
				"finalizeDialog",
				[
					{
						label:"Cancel",
						attr:{href:"#", "data-dismiss":"modal", "aria-label":"Close", class:"btn btn-primary small"}
					},
					{
						label:"Confirm",
						attr:{href:"#", class:"btn btn-primary small confirm"}	
					}
				]
			));
			div.find(".actions .confirm").click(function(e){
				e.preventDefault();
                var project_id = $(this).attr("data-project_id"),
                	user_id = $(this).attr("data-user_id");
                storify.brand.completion.completeCompletion(project_id, user_id);
			});
			$("body").append(div);

			div = $(storify.template.simpleModal(
				{
					titlehtml:`Pay for all submissions`,
					bodyhtml:`<p>This will confirm all submissions and we will arrange to pay all Creators.</p>`	
				},
				"finalizeAllDialog",
				[
					{
						label:"Cancel",
						attr:{href:"#", "data-dismiss":"modal", "aria-label":"Close", class:"btn btn-primary small"}
					},
					{
						label:"Confirm",
						attr:{href:"#", class:"btn btn-primary small confirm"}	
					}
				]
			));
			div.find(".actions .confirm").click(function(e){
				 e.preventDefault();
                var project_id = $(this).attr("data-project_id");
                storify.brand.completion.completeCompletionAll(project_id);
			});
			$("body").append(div);

			div = $(storify.template.simpleModal(
				{
					titlehtml:`Close project`,
					bodyhtml:`<p>This project is done. We will close it now.</p>`	
				},
				"closeProjectDialog",
				[
					{
						label:"Cancel",
						attr:{href:"#", "data-dismiss":"modal", "aria-label":"Close", class:"btn btn-primary small"}
					},
					{
						label:"Confirm",
						attr:{href:"#", class:"btn btn-primary small confirm"}	
					}
				]
			));
			div.find(".actions .confirm").click(function(e){
				e.preventDefault();
                var project_id = $(this).attr("data-project_id");
                storify.brand.completion.closeProject(project_id);
			});
			$("body").append(div);

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
			action_cont.append(
				$("<button>").addClass("btn btn-primary small").text("Pay")
					.click(function(e){
						$("#finalizeDialog .modal-footer .btn:eq(1)").attr({"data-user_id":data.user_id});
						$("#finalizeDialog").modal("show");
					})
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
		storify.brand.completion.addElementIfNotExist();

		$("#final-content").empty();
		var alldone = true,
			widthData = false;
		$.each(data, function(index,value){
			widthData = true;
			$("#final-content").append(storify.brand.completion.createCompletionItem(value));
			if(value.status != "close"){
				alldone = false;
			}
		});

		if(withData){
			if(alldone){
				$("#final-content").append(
					$("<div>").addClass("text-right")
						.append(
							$("<button>").addClass("btn btn-primary small").text("Close")
								.click(function(e){
									/*$("#closeProjectDialog .modal-footer .btn:eq(1)").attr({"data-project_id":data.user_id});*/
									$("#closeProjectDialog").modal("show");
								})
						)
				);	
			}else{
				$("#final-content").append(
					$("<div>").addClass("text-right")
						.append(
							$("<button>").addClass("btn btn-primary small").text("Pay All")
								.click(function(e){
									/*$("#finalizeAllDialog .modal-footer .btn:eq(1)").attr({"data-user_id":data.user_id});*/
									$("#finalizeAllDialog").modal("show");
								})
						)
				);
			}
		}else{
			$("#final-content").append($("<div>").append($("<p>").text("No submissions have been made, yet.")));
			$("#final-content").append(
					$("<div>").addClass("text-right")
						.append(
							$("<button>").addClass("btn btn-primary small").text("Close")
								.click(function(e){
									/*$("#closeProjectDialog .modal-footer .btn:eq(1)").attr({"data-project_id":data.user_id});*/
									$("#closeProjectDialog").modal("show");
								})
						)
				);	
		}
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
            	$("#closeProjectDialog .modal-footer .btn:eq(1)").attr({"data-project_id":project_id});
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
				storify.brand.deliverable.getList();
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
				storify.brand.deliverable.getList();	
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
				
				if(rs.project_id){
					//go to close project page
					window.location.href = "/user@"+rs.userid+"/projects/closed/"+rs.project_id;
				}else{
					alert("error, project id missing");
				}
			}
		})
	}
};