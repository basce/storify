var storify = storify || {};
storify.creator = storify.creator || {};

storify.creator.deliverable = {
	addElementIfNotExist:function(){
		if( !$("#update_submission").length ){
			$("body").append(
				$("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"update_submission"})
					.append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
						.append($("<div>").addClass("modal-content")
							.append($("<div>").addClass("modal-header")
								.append($("<h5>").addClass("modal-title").text("Submission"))
								.append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal","aria-label":"Close"}).append($("<span>").attr({"aria-hidden":"hidden"}).html("&times;")))
							)
                            .append($("<div>").addClass("modal-body deliverable_items")
                                .append($("<div>").addClass("main_block")
                                    .append($("<div>").addClass("input_group")
                                        .append($("<div>").addClass("label").text("URL"))
                                        .append($("<input>").attr({type:"text"}).addClass("form-control input-url"))
                                    )
                                    .append($("<div>").addClass("input_group")
                                        .append($("<div>").addClass("label").text("Instruction (optional)"))
                                        .append($("<textarea>").addClass("form-control input-instruction").attr({rows:4}))    
                                    )
                                )
                                .append($("<div>").addClass("reply_block")
                                    .append($("<label>").text("Status"))
                                    .append($("<span>").addClass("value input-status").text("Reject"))
                                    .append($("<p>").addClass("reason_value"))
                                    .append($("<small>").addClass("input-date"))
                                )
                                .append($("<div>").addClass("history_block"))
                            )
							.append($("<div>").addClass("modal-footer")
								.append($("<button>").addClass("btn btn-primary small confirmsubmission").text("Update"))
							)
						)
					)
			);
			$("#update_submission button.confirmsubmission").click(storify.creator.deliverable.submitclick);
		}
	},
    displayDialog:function(data){
        if(data.URL){
            $("#update_submission .input-url").val(data.URL);
        }else{
            $("#update_submission .input-url").val("");
        }
        if(data.submission_remark){
            $("#update_submission .input-instruction").val(data.submission_remark);
        }else{
            $("#update_submission .input-instruction").val("");
        }
        $("#update_submission .input-status").removeClass("item-accepted item-rejected item-waiting item-pending");
        $("#update_submission .reason_value").text("");
        $("#update_submission .confirmsubmission").attr({"data-id":data.deliverable_id});
        if(data.response_status){
            $("#update_submission .reply_block").removeClass("hide");
            if(data.response_status == "accepted"){
                //this won't show up
                $("#update_submission .input-date").text(data.response_date);
                $("#update_submission .input-status").addClass("item-accepted").text("Accepted");
                $("#update_submission .confirmsubmission").text("N/A");
            }else if(data.response_status == "rejected"){
                $("#update_submission .input-date").text(data.response_date);
                $("#update_submission .input-status").addClass("item-rejected").text("Rejected");
                $("#update_submission .confirmsubmission").text("Re-submit")
                if(data.response_remark){
                    $("#update_submission .reason_value").text(data.response_remark);
                }
            }else{
                //pending
                $("#update_submission .input-status").addClass("item-waiting").text("Pending");
                $("#update_submission .confirmsubmission").text("Update")
            }
        }else{
            $("#update_submission .reply_block").addClass("hide");
            $("#update_submission .confirmsubmission").text("Submit")
        }

        $("#update_submission .history_block").empty();
        if(data.history_id){
            $("#update_submission .history_block").append($("<a>").attr({href:"#"}).click(function(e){
                e.preventDefault();
                var _this = $(this);
                storify.creator.deliverable.getHistory(data.deliverable_id, function(data2){
                    //console.log(data2);
                    $.each(data2.data, function(index,value){
                        _this.parent().append(storify.creator.deliverable.createHistoryBlock(value));
                    });
                    _this.remove();
                });
            }));
        }
        $("#update_submission").modal("show");
    },
    _gettingHistory:false,
    getHistory:function(deliverable_id, callback){
        if(storify.creator.deliverable._gettingHistory) return;
        storify.creator.deliverable._gettingHistory = true;
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{
                deliverable_id:deliverable_id,
                method:"getDeliverableHistory"
            },
            error:function(request, status, error){
                storify.creator.deliverable._gettingHistory = false;
            },
            success:function(rs){
                storify.creator.deliverable._gettingHistory = false;
                if(rs.error){
                    alert(rs.msg);
                }else{
                    if(callback) callback(rs);
                }
            }
        })
    },
    createHistoryBlock:function(data){
        return $("<div>").addClass("history_item")
            .append($("<div>").addClass("submission_block")
                        .append($("<div>").addClass("item_row")
                                    .append($("<div>").addClass("label").text("URL"))
                                    .append($("<div>").addClass("value").text(data.URL))
                            )
                        .append($("<div>").addClass("item_row")
                                    .append($("<div>").addClass("label").text("Instruction"))
                                    .append($("<div>").addClass("value").text(data.remark))
                            )
                        .append($("<div>").addClass("item_row")
                                    .append($("<div>").addClass("label").text("Submit Time"))
                                    .append($("<div>").addClass("value").text(data.submit_tt))
                            )
                )
            .append($("<div>").addClass("reply_block")
                        .append($("<div>").addClass("item_row")
                                    .append($("<div>").addClass("label").text("Status"))
                                    .append($("<div>").addClass("value").text(data.response_status))
                            )
                        .append($("<div>").addClass("item_row")
                                    .append($("<div>").addClass("label").text("Reason"))
                                    .append($("<div>").addClass("value").text(data.response_remark))
                            )
                        .append($("<div>").addClass("item_row")
                                    .append($("<div>").addClass("label").text("Reply Time"))
                                    .append($("<div>").addClass("value").text(data.response_tt))
                            )
                );
    },
    _gettingDeliverable:false,
    getList:function(callback){
    	if(storify.creator.deliverable._gettingDeliverable) return;
        storify.creator.deliverable._gettingDeliverable = true;
        $.ajax({
            type:"POST",
            dataType:'json',
            data:{
                project_id:storify.project._project_id,
                method:"getDeliverable"
            },
            error:function(request, status, error){
                storify.creator.deliverable._gettingDeliverable = false;
            },
            success:function(rs){
                storify.creator.deliverable._gettingDeliverable = false;
                if(rs.error){
                    alert(rs.msg);
                }else{
                    storify.creator.deliverable.display(rs.data, callback);
                }
            }
        });
    },
    createDeliverableItem:function(data){
    	storify.creator.deliverable.addElementIfNotExist();
    	var d = $("<div>").addClass("deliverable-creator-item").attr({"deliverable-id":data.deliverable_id}),
            temp_creator = storify.project.user.getUserDetail(data.user_id),
            temp_remark = null,
            temp_status = null,
            temp_action = null,
            temp_history = null;

        if(data.submission_remark){
            temp_remark = $("<div>").addClass("single_block")
                                .append($("<label>").text("Remark"))
                                .append(document.createTextNode(data.submission_remark));
        }
        if(data.response_status == "accepted" ){
            temp_status = $("<div>").addClass("single_block")
                                .append($("<label>").text("Status"))
                                .append($("<span>").addClass("item-status").text(data.response_status))
                                .append($("<small>").text(data.response_date));
            d.addClass("item-accepted");
        }else if(data.response_status == "rejected"){
            temp_status = $("<div>").addClass("single_block")
                                .append($("<label>").text("Status"))
                                .append($("<span>").addClass("item-status").text(data.response_status))
                                .append($("<p>").text(data.response_remark))
                                .append($("<small>").text(data.response_date));
            d.addClass("item-rejected");
            temp_action = $("<div>").addClass("bottom_panel")
                .append(
                    $("<button>").addClass("btn btn-success small")
                        .text("Re-submit")
                        .click(function(e){
                            //call out dialog
                            storify.creator.deliverable.displayDialog(data);
                        })
                );
        }else{
            if(data.URL){
                temp_status = $("<div>").addClass("single_block")
                                .append($("<small>").text(data.response_date))
                                .append($("<label>").text("Status"))
                                .append($("<span>").addClass("item-status").text("pending"));
                d.addClass("item-pending");
                temp_action = $("<div>").addClass("bottom_panel")
                .append(
                    $("<button>").addClass("btn btn-success small")
                        .text("Edit")
                        .click(function(e){
                            //call out dialog
                            storify.creator.deliverable.displayDialog(data);
                        })
                );
            }else{
                d.addClass("item-pending");
                temp_action = $("<div>").addClass("bottom_panel")
                .append(
                    $("<button>").addClass("btn btn-success small")
                        .text("Submit Deliverable")
                        .click(function(e){
                            //call out dialog
                            storify.creator.deliverable.displayDialog(data);
                        })
                );
            }
        }

        if(data.history_id){
            temp_history = $("<a>").attr({href:"#"}).text("history")
                                .click(function(e){
                                    e.preventDefault();
                                    var _this = $(this);
                                    storify.creator.deliverable.getHistory(data.deliverable_id, function(data2){
                                         $.each(data2.data, function(index,value){
                                            _this.parent().append(storify.creator.deliverable.createHistoryBlock(value));
                                        });
                                        _this.remove();
                                    });
                                });
        }
        
        if(data.URL){
            d.append($("<div>").addClass("top_panel")
                .append($("<div>").addClass("single_block")
                    .append($("<label>").text("Submission"))
                    .append($("<input>").attr({type:"text",readonly:true})
                        .val(data.URL)
                        .click(function(e){
                            e.preventDefault();
                            this.setSelectionRange(0, this.value.length);
                        })
                    )
                )
                .append(temp_remark)
                .append(temp_status)
                .append(temp_history)
            )
            .append(temp_action);
        }else{
           d.append($("<div>").addClass("top_panel")
                    .append($("<div>").addClass("single_block")
                        .append($("<p>").text("waiting for submit"))
                    )
            )
            .append(temp_action); 
        }
	    return d;
    },
    display:function(data, callback){
    	$(".deliverable-groups").empty();
        var photo_type = 0,
            video_type = 0;
        $.each(data, function(index,value){
        	var a = $("<div>").addClass("deliverable-group"),
                b = $("<div>").addClass("deliverable-creator-group"),
                c = $("<div>").addClass("deliverable-creator-summary"),
                creator_not_submit = null,
                tempname = "",
                tempuser = storify.project.user.data.slice(0);

            if(value.type == "photo"){
                photo_type++;
                tempname = "Photo #"+photo_type;
            }else{
                video_type++;
                tempname = "Video #"+video_type;
            }
            a.append($("<h3>").text(tempname));
            if(value.deliverable_remark){
                a.append($("<div>").addClass("deliverable-remark").text(value.deliverable_remark));
            }
            b.append(storify.creator.deliverable.createDeliverableItem(value));

	        a.append(b);
            a.append(c);
            $(".deliverable-groups").append(a);
        });

        if( typeof callback === "function" ){
			callback();
		}
    },
    _submitting_submission:false,
    submit_submission:function(deliverable_id, URL, remark){
        if(storify.creator.deliverable._submitting_submission) return;
        storify.creator.deliverable._submitting_submission = true;
        storify.loading.hide();
        $.ajax({
            type:"POST",
            dataType:'json',
            data:{
                deliverable_id:deliverable_id,
                URL:URL,
                remark:remark,
                method:"makeSubmission"
            },
            error:function(request, status, error){
                storify.creator.deliverable._submitting_submission = false;
            },
            success:function(rs){
                storify.creator.deliverable._submitting_submission = false;
                if(rs.error){
                    alert(rs.msg);
                }else{
                    //refresh deliverable
                    storify.creator.deliverable.getList(function(){
                        $("#update_submission").modal("hide");
                        storify.loading.hide();
                        //highline item
                        $('*[deliverable-id="'+deliverable_id+'"]').addClass("elementNeedFocus");
                    });
                }
            }
        });
    },
    submitclick:function(e){
    	e.preventDefault();
        //get deliverable_id
        var deliverable_id = $(this).attr("data-id"),
            URL = $("#update_submission .input-url").val(),
            remark = $("#update_submission .input-instruction").val();

        if(URL){
            storify.creator.deliverable.submit_submission(deliverable_id, URL, remark);
        }else{
            alert("Submission URL cannot be empty.");
        }
    }
};