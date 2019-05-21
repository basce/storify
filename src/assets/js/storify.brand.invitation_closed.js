var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.invitation_closed = {
	addElementIfNotExist:function(){
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
        $(a+" .modal-body strong").text(data.display_name+" ( "+data.user_email+ " ) ");
        switch(data.invitation_status){
        	case "pending":
        		b.addClass("item-pending").text("Waiting");
        		c.text("Withdraw Invitation").attr({"data-id":data.invitation_id, "data-command_type":1});
        	break;
        	case "rejected":
        		b.addClass("item-rejected").text("Rejected");
        		c.text("Resend Invitation").attr({"data-id":data.user_id, "data-command_type":2});
        	break;
        	case "accepted":
        		/*
        		b.addClass("item-accepted").text("Accepted");
        		c.text("Remove Creator from Project").attr({"data-id":data.user_id, "data-command_type":3});
        		*/
        		b.addClass("item-accepted").text("Accepted");
        		c.text("Ok").attr({"data-id":data.user_id, "data-command_type":3});
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
                a.addClass("item-pending default-pointer").attr({title:data.display_name});
            break;
            case "accepted":
                a.addClass("item-accepted default-pointer").attr({title:data.display_name});
            break;
            case "rejected":
                a.addClass("item-rejected default-pointer").attr({title:data.display_name});
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
