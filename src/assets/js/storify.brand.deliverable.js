var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.deliverable = {
	addElementIfNotExist:function(){
        var div;
		if( !$("#reject_submission").length ){
            div = $(storify.template.simpleModal(
                {
                    titlehtml:`Reject Submission`,
                    bodyhtml:`
                    <h3>Let us know why the Creator's submission is rejected so that he or she can update the submission accordingly. (Optional)</h3>
                    <textarea class="form-control" rows="4"></textarea>
                    `
                },
                "reject_submission",
                [   
                    {
                        label:"Cancel",
                        attr:{type:"button", class:"btn btn-primary small", "data-dismiss":"modal"}
                    },
                    {
                        label:"Reject",
                        attr:{type:"button", class:"btn btn-primary small confirmreject"}
                    }
                ]
            ));

            div.find(".actions .confirmreject").click(storify.brand.deliverable.reject_click);

            $("body").append(div);
		}
        if( !$("#reject_reason").length ){
            div = $(storify.template.simpleModal(
                {
                    titlehtml:`Reject Submission`,
                    bodyhtml:`
                    <p class="reason"></p>
                    `
                },
                "reject_reason",
                [   
                    {
                        label:"Edit",
                        attr:{type:"button", class:"btn btn-primary small edit"}
                    },
                    {
                        label:"Close",
                        attr:{type:"button", class:"btn btn-primary small", "data-dismiss":"modal"}
                    }
                ]
            ));

            div.find(".actions .edit").click(storify.brand.deliverable.edit_reason);

            $("body").append(div);
        }
        if (!$("#downloadLinkModal").length) {
            div = $(storify.template.simpleModal(
                {
                    titlehtml:``,
                    bodyhtml:`
                    <a class="filename" download></a>
                    <div class="filesize"></div>
                    <div class="filemime"></div>
                    `
                },
                "downloadLinkModal",
                [   
                    {
                        label:"download",
                        attr:{type:"button", class:"btn btn-primary small download", href:"", download:""}
                    }
                ]
            ));

            $("body").append(div);
        }
	},
	_submitting_response:false,
	response:function($submission_id, $action, $action_remark, callback){
        if(storify.brand.deliverable._submitting_response) return;
        storify.brand.deliverable._submitting_response = true;

        $.ajax({
            type:"POST",
            dataType:'json',
            data:{
                method:"response_submission",
                submission_id:$submission_id,
                status:$action,
                status_remark:$action_remark
            },
            error:function(){
                if( typeof callback === "function" ){
					callback();
				}
            },
            success:function(res){
                storify.brand.deliverable._submitting_response = false;
                if(res.error){
                    alert(res.msg);
                }else{
                    storify.brand.completion.getCompletion(storify.project._project_id);
                    storify.brand.deliverable.getList(function(){
                        $("#reject_submission").modal("hide");
                        $(".item-rejected[data-id='"+$submission_id+"'] a.item-status").click();
                        if( typeof callback === "function" ){
							callback();
						}
                    });
                }
            }
        });
    },
    _gettingHistory:false,
    getHistory:function(deliverable_id, user_id, callback){
        if(storify.brand.deliverable._gettingHistory) return;
        storify.brand.deliverable._gettingHistory = true;
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{
                deliverable_id:deliverable_id,
                creator:user_id,
                method:"getDeliverableHistory"
            },
            error:function(request, status, error){
                storify.brand.deliverable._gettingHistory = false;
            },
            success:function(rs){
                storify.brand.deliverable._gettingHistory = false;
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
                                    .append($("<div>").addClass("value").text(data.response_status.charAt(0).toUpperCase() + data.response_status.slice(1)))
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
    	if(storify.brand.deliverable._gettingDeliverable) return;
        storify.brand.deliverable._gettingDeliverable = true;
        $.ajax({
            type:"POST",
            dataType:'json',
            data:{
                project_id:storify.project._project_id,
                method:"getDeliverable"
            },
            error:function(request, status, error){
                storify.brand.deliverable._gettingDeliverable = false;
            },
            success:function(rs){
                storify.brand.deliverable._gettingDeliverable = false;
                if(rs.error){
                    alert(rs.msg);
                }else{
                    storify.brand.deliverable.display(rs, callback);
                }
            }
        });
    },
    createDeliverableItem:function(data){
    	storify.brand.deliverable.addElementIfNotExist();
    	var d = $("<div>").addClass("deliverable-creator-item").attr({"data-id":data.id}),
            temp_creator = storify.project.user.getUserDetail(data.creator_id),
            temp_remark = null,
            temp_status = null,
            temp_action = null,
            temp_history = null;

        if(data.remark){
            temp_remark = $("<div>").addClass("single_block urldescription")
                                .append($("<p>").text(data.remark));
        }
        if(data.status == "accepted" || data.status == "rejected"){
            if(data.status == "accepted"){
                temp_status = $("<div>").addClass("status_block")
                                .append($("<span>").attr({title:data.admin_remark}).addClass("item-status").text(data.status.charAt(0).toUpperCase() + data.status.slice(1)));
                d.addClass("item-accepted");
            }else{
                console.log(data);
                temp_status = $("<div>").addClass("status_block")
                                .append($("<a>").attr({href:"#", title:data.admin_remark}).addClass("item-status").text(data.status.charAt(0).toUpperCase() + data.status.slice(1))
                                    .click(function(e){
                                        e.preventDefault();
                                        var remark = $(this).attr("title");
                                        $("#reject_reason .reason").text(remark ? remark : "no reason given.");

                                        $("#reject_submission textarea").val(remark ? remark : "");
                                        $("#reject_submission button.confirmreject").attr({"data-id":data.id});

                                        $("#reject_reason").modal("show");
                                    })
                                );
                d.addClass("item-rejected");
                $("#reject_reason button.edit").attr({"data-id":data.id});
            }
        }else{
            d.addClass("item-pending");
            temp_action = $("<div>").addClass("bottom_panel")
                .append(
                    $("<button>").addClass("btn btn-success small")
                        .text("Accept")
                        .click(function(e){
                            //accept submission
                            storify.brand.deliverable.response(data.id, "accepted","");
                        })
                    )
                .append(
                    $("<button>").addClass("btn btn-danger small")
                        .text("Reject")
                        .click(function(e){
                            //reject submission
                            $("#reject_submission textarea").val("");
                            $("#reject_submission button.confirmreject").attr({"data-id":data.id});
                            $("#reject_submission").modal("show");
                        })
                );
        }
        if(data.history_id){
            /*no longer in used*/
            temp_history = $("<a>").attr({href:"#"}).text("history")
                                .click(function(e){
                                    e.preventDefault();
                                    var _this = $(this);
                                    storify.brand.deliverable.getHistory(data.deliverable_id, data.user_id, function(data2){
                                         $.each(data2.data, function(index,value){
                                            _this.parent().append(storify.brand.deliverable.createHistoryBlock(value));
                                        });
                                        _this.remove();
                                    });
                                });
        }

        if(+data.file_id){
            d.append($("<div>").addClass("top_panel")
                    .append($("<small>").text(data.submit_tt))
                    .append($("<div>").addClass("single_block")
                        .append($("<label>").append($("<span>").css({color:"#999","font-size":".9em"}).text("ASSET-"+storify.core.leadingZero(data.id, 9))).prepend($("<i>").addClass("fa fa-"+(data.type == "photo"?"camera":"video-camera")).css({"margin-right":"5px"})))
                        .append($("<div>").addClass("file-container")
                            .append($("<div>").addClass("file-download-link").text(storify.brand.deliverable.shortenFileName(data.filename)+" ("+data.mime+")")
                                            .append($("<i>").addClass("fa fa-arrow-circle-down").css({"margin-left":".5rem"}))
                                            .click(function(e){
                                                e.preventDefault();
                                                storify.brand.deliverable._showDownloadDialog(data.file_id);
                                            })
                                )
                        )
                    )
                    .append(temp_remark)
                    .append(temp_status)
                    .append(temp_history)
            )
            .append(temp_action);

        } else {
            d.append($("<div>").addClass("top_panel")
                    .append($("<small>").text(data.submit_tt))
                    .append($("<div>").addClass("single_block")
                        .append($("<label>").append($("<span>").css({opacity:0.65}).text("ASSET-000000000".slice(0, -1*(data.id+"").length) + data.id)).prepend($("<i>").addClass("fa fa-"+(data.type == "photo"?"camera":"video-camera")).css({"margin-right":"5px"})))
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
        }
	    return d;
    },
    _showDownloadDialog:function(id){
        storify.loading.show();
        $.ajax({
            method: "POST",
            dataType: "json",
            data: {
                method: "getDownloadLink",
                id:id
            },
            success: function(rs){
                storify.loading.hide();
                if(rs.error){
                    alert(rs.msg);
                } else {
                    $("#downloadLinkModal").find(".filename")
                                                .attr({href:rs.filelink, download:rs.filename, target:"_blank"})
                                                .text(storify.brand.deliverable.shortenFileName(rs.filename)+" ("+rs.filemime+")")
                                                .append($("<i>").addClass("fa fa-arrow-circle-down").css({"margin-left":".5rem"}));
                    $("#downloadLinkModal").find(".filesize").text("");
                    $("#downloadLinkModal").find(".filemime").text();
                    $("#downloadLinkModal").find(".download").attr({href:rs.filelink, download:rs.filename, target:"_blank"})
                    $("#downloadLinkModal").modal("show");
                }
            }

        })
    },
    display:function(odata, callback){
    	$(".deliverable-groups").empty();
        var photo_type = 0,
            video_type = 0;
            data = odata.data,
            withData = false;

        var photo_total = 0,
            photo_waiting = 0,
            photo_approved = 0,
            photo_rejected = 0,
            photo_submitted = 0,
            video_total = 0,
            video_waiting = 0,
            video_approved = 0,
            video_rejected = 0,
            video_submitted = 0;

        photo_total = parseInt(odata.no_of_photo, 10);
        video_total = parseInt(odata.no_of_video, 10);

        $.each(data, function(index,value){
            withData = true;
            $.each(value.data, function(index2, value2){
                if(value2.type == "photo"){
                    switch(value2.status){
                        case "accepted":
                            photo_approved++;
                            photo_submitted++;
                        break;
                        case "rejected":
                            photo_rejected++;
                            photo_submitted++;
                        break;
                        default:
                            photo_waiting++;
                            photo_submitted++;
                        break;
                    }
                }else{
                    switch(value2.status){
                        case "accepted":
                            video_approved++;
                            video_submitted++;
                        break;
                        case "rejected":
                            video_rejected++;
                            video_submitted++;
                        break;
                        default:
                            video_waiting++;
                            video_submitted++;
                        break;
                    }
                }
            });
        });
        if(withData){
            $(".deliverable-groups")
                .append(
                    $("<h5>").append($("<i>").addClass("fa fa-camera").css({"margin-right":"5px"}))
                        .append(document.createTextNode("Total "+photo_submitted+"/"+photo_total+" . Waiting "+photo_waiting+" . Approved "+photo_approved+" . Rejected "+photo_rejected))
                )
                .append(
                $("<h5>").append($("<i>").addClass("fa fa-video-camera").css({"margin-right":"5px"}))
                        .append(document.createTextNode("Total "+video_submitted+"/"+video_total+" . Waiting "+video_waiting+" . Approved "+video_approved+" . Rejected "+video_rejected))
                );
        }else{
            $(".deliverable-groups").append($("<div>").append($("<p>").text("No submissions have been made, yet.")));
        }

        $.each(data, function(index,value){
        	var a = $("<div>").addClass("deliverable-group"),
                b = $("<div>").addClass("deliverable-creator-group"),
                c = $("<div>").addClass("deliverable-creator-summary"),
                creator_not_submit = null,
                tempname = "",
                count_done = 0,
                count_pending = 0,
                count_reject = 0,
                total_expect = 0,
                tempuser = storify.project.user.data.slice(0);

            var ctempuser = storify.project.user.getUserDetail(value.user_id);

            total_expect = parseInt(odata.no_of_photo, 10) + parseInt(odata.no_of_video, 10);

            a.append($("<div>").addClass("creator_cont")
                .append($("<div>").addClass("profile-image")
                            .attr({title:ctempuser.igusername + " (" + ctempuser.display_name+ ")"})
                            .css({"background-image":"url("+ctempuser.profile_image+")"})
                )
                .append($("<p>").text(" (" + ctempuser.display_name+ ")")
                            .prepend($("<span>").text("@"+ctempuser.igusername))
                    )
            );

            $.each(value.data, function(index2, value2){
            	switch(value2.status){
            		case "accepted":
            			count_done++;
            		break;
            		case "rejected":
            			count_reject++;
            		break;
            		default:
            			count_pending++;
            		break;
            	}
            	b.append(storify.brand.deliverable.createDeliverableItem(value2));

	        	tempuser = tempuser.filter(function(creator){
	                return creator.user_id != value2.user_id;
	            });
            });

            if(count_done){
            	c.append($("<span>").addClass("item-accepted").text(count_done+" accepted"));
            }
            if(count_pending){
            	c.append($("<span>").addClass("item-pending").text(count_pending+" waiting"));
            }
            if(count_reject){
            	c.append($("<span>").addClass("item-rejected").text(count_reject+" rejected"));
            }

            c.append($("<span>").addClass("item").text(" / "+total_expect+" total"));

            tempuser = tempuser.filter(function(creator){
	            return creator.role != "admin";
	        });

            /*
	        if(tempuser.length){
	        	var temp_submit_group = $("<div>").addClass("not-submit-group");
	        	$.each(tempuser, function(index3,value3){
                    temp_submit_group.append(
                        $("<div>").addClass("profile-image")
                            .attr({title:value3.display_name})
                            .css({"background-image":"url("+value3.profile_image+")"})
                    );
                });

                creator_not_submit = $("<div>").addClass("not-submit");
                creator_not_submit.append($("<h3>").text("Waiting for submit"));
                creator_not_submit.append(temp_submit_group);
	        }
            */

	        a.append(b);
            /*a.append(c);*/
            a.append(creator_not_submit);
            $(".deliverable-groups").append(a);
        });

        if( typeof callback === "function" ){
			callback();
		}
    },
    reject_click:function(e){
    	e.preventDefault();
    	var a = "#reject_submission";
        if($(a+" button.confirmreject").attr("data-id")){
            storify.brand.deliverable.response($(a+" button.confirmreject").attr("data-id"), "rejected", $(a+" textarea").val());
        }
    },
    edit_reason:function(e){
        $("#reject_reason").modal("hide");
        $("#reject_submission").modal("show");
    },
    shortenFileName:function(input){
        var a = input.slice(0, input.lastIndexOf(".")),
            b = input.slice(input.lastIndexOf("."));

        if(a.length > 27){
            return a.slice(0,24)+"..."+b;
        }else{
            return input;
        }
    }
};