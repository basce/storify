var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.invitation_closed = {
	addElementIfNotExist:function(){
		if( !$("#single_invitation_dialog").length ){
			$("body").append(
				$("<modal>").addClass("modal")
					.attr({tabindex:"-1", role:"dialog", id:"single_invitation_dialog"})
					.append(
						$("<div>").addClass("modal-dialog modal-dialog-centered")
							.attr({role:"document"})
							.append(
								$("<div>").addClass("modal-content")
									.append(
										$("<div>").addClass("modal-header")
											.append(
												$("<h5>").addClass("modal-title")
													.text("Manage Invitation")
											)
											.append(
												$("<button>").addClass("close")
													.attr({type:"button", "data-dismiss":"modal", "aria-label":"Close"})
													.append(
														$("<span>").attr({"aria-hidden":true})
															.html("&times;")
													)
											)
									)
									.append(
										$("<div>").addClass("modal-body")
											.append(
												$("<div>").addClass("container")
													.append(
														$("<div>").addClass("row")
															.append(
																$("<div>").addClass("col-3")
																	.append($("<div>").addClass("profile-image"))
															)
															.append(
																$("<div>").addClass("col-9")
																	.append(
																		$("<div>").append($("<strong>"))
																	)
																	.append(
																		$("<div>").append(document.createTextNode("Status : "))
																			.append(
																				$("<span>").addClass("status")
																			)
																	)
																	.append(
																		$("<div>").addClass("remark")
																	)
															)
													)
											)
									)
									.append(
										$("<div>").addClass("modal-footer")
											.append(
												$("<div>").addClass("text-center")
													.append(
														$("<button>").addClass("btn btn-primary")
															.text("invite")
													)
											)
									)
							)
					)
			);

			$("#single_invitation_dialog .modal-footer button").click(storify.brand.invitation_closed.dialog_click);
		}
	},
	_updatingInvitation:false,
	dialog_click:function(e){
		// click on dialog button
		e.preventDefault();

		if( !+storify.project._project_id ){
			console.log("storify.project._project_id not a number", storify.project._project_id);
			return;
		}

		var a = $("#single_invitation_dialog .modal-footer button"),
			id = $(this).attr("data-id"),
			command_type = +$(this).attr("data-command_type");
		
		if( command_type == 3){
			$("#single_invitation_dialog").modal("hide");
			return;
		}
		/*
		if( command_type ){
			if( storify.brand.invitation_closed._updatingInvitation ) return;
			storify.brand.invitation_closed._updatingInvitation = true;
			$.ajax({
				type: 	"POST",
				dataType: "json",
				data:{
					project_id: 	storify.project._project_id,
					command_type: 	command_type,
					id: 			id,
					method: 		"editInvitation"
				},
				success: function(rs){
					storify.brand.invitation_closed._updatingInvitation = false;
					storify.brand.invitation_closed.getList(function(){
						$("#single_invitation_dialog").modal("hide");
					});
				}
			});
		}
		*/
	},
	resetForm:function(){
		storify.brand.invitation_closed.addElementIfNotExist();
		if($("#invite").length){
			$("#invite")[0].selectize.clear(true);
		}
		$(".invite-group").empty();
		$(".invite-summary").empty();
	},
	displaySingleInvite:function(data){
		var a = "#single_invitation_dialog",
			b = $(a+" .modal-body .status"),
			c = $(a+" .modal-footer button");
		$(a+" .profile-image").css({"background-image":"url("+data.profile_image+")"});
        $(a+" .modal-body strong").empty()
        	.append($("<a>").attr({href:"/"+data.igusername, target:"_blank"}).text('@'+data.igusername))
        	.append(document.createTextNode(" ("+data.user_email+ ")"));
        switch(data.invitation_status){
        	case "rejected":
        		b.addClass("item-rejected").text("Rejected");
        		c.text("ok").attr({"data-id":data.user_id, "data-command_type":3});
        	break;
        	case "accepted":
        		/*
        		b.addClass("item-accepted").text("Accepted");
        		c.text("Remove Creator from Project").attr({"data-id":data.user_id, "data-command_type":3});
        		*/
        		b.addClass("item-accepted").text("Accepted");
        		c.text("Ok").attr({"data-id":data.user_id, "data-command_type":3});
        	break;
        	case "pending":
        	default:
        		b.addClass("item-pending").text("Waiting");
        		c.text("ok").attr({"data-id":data.invitation_id, "data-command_type":3});
        	break;
        }
        if(data.remark){
        	$(a+" .remark").css({display:"block"}).text(data.remark);
        }else{
        	$(a+" .remark").css({display:"none"}).text("");
        }
        $(a).modal("show");
	},
	createItem:function(data){
		var a = $("<div>").addClass("invite-item")
                    .append($("<div>").addClass("profile-image").css({"background-image":"url("+data.profile_image+")"}))
                    .click(function(e){
                        e.preventDefault();
                        storify.brand.invitation_closed.displaySingleInvite(data);
                    });
        switch(data.invitation_status){
            case "pending":
                a.addClass("item-pending").attr({title:'@'+data.igusername});
            break;
            case "accepted":
                a.addClass("item-accepted").attr({title:'@'+data.igusername});
            break;
            case "rejected":
                a.addClass("item-rejected").attr({title:'@'+data.igusername});
            break;
        }

        return a;
	},
	display:function(data, callback){
		storify.brand.invitation_closed.resetForm();

		var accepted_count = 0,
			rejected_count = 0,
			waiting_count = 0;

		$.each(data, function(index,value){
			$(".invite-group").append(storify.brand.invitation_closed.createItem(value));
			switch(value.invitation_status){
				case "pending":
				case "waiting":
					waiting_count++;
				break;
				case "accepted":
					accepted_count++;
				break;
				case "rejected":
					rejected_count++;
				break;
			}
		});

		$(".no_of_invited").text(accepted_count);

		/*
		if(accepted_count){
			$(".invite-summary").append($("<span>").addClass("item-accepted").text(accepted_count+" accepted"));
		}
		if(waiting_count){
			$(".invite-summary").append($("<span>").addClass("item-pending").text(waiting_count+" waiting"));
		}
		if(rejected_count){
			$(".invite-summary").append($("<span>").addClass("item-rejected").text(rejected_count+" rejected"));
		}
		*/
		if( typeof callback === "function" ){
			callback();
		}
	},
	_gettingInvitation:false,
	getList:function(callback){
		if(storify.brand.invitation_closed._gettingInvitation) return;
		storify.brand.invitation_closed.resetForm();
		storify.brand.invitation_closed._gettingInvitation = true;
		$.ajax({
			type: "POST",
			dataType: "json",
			data:{
				project_id:storify.project._project_id,
				method:"getInvitationList"
			},
			success:function(rs){
				storify.brand.invitation_closed._gettingInvitation = false;
				if(rs.error){
					alert(rs.msg);
				}else{
					storify.brand.invitation_closed.display(rs.data, callback);
				}
			}
		});
	}
};
