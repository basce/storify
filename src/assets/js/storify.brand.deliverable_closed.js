var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.deliverable_closed = {
	addElementIfNotExist:function(){
        var div;
		if( !$("#reject_submission").length ){
            /*
			$("body").append(
				$("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"reject_submission"})
					.append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
						.append($("<div>").addClass("modal-content")
							.append($("<div>").addClass("modal-header")
								.append($("<h5>").addClass("modal-title").text("Reject Submission"))
								.append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal","aria-label":"Close"}).append($("<span>").attr({"aria-hidden":"hidden"}).html("&times;")))
							)
							.append($("<div>").addClass("modal-body deliverable_items")
								.append($("<h3>").text("Provide a reason why the submission is rejected so creator can make amendment according to you comments. (optional)"))
								.append($("<textarea>").addClass("form-control").attr({rows:4}))
							)
							.append($("<div>").addClass("modal-footer")
								.append($("<button>").addClass("btn btn-primary small").attr({"data-dismiss":"modal"}).text("Cancel"))
								.append($("<button>").addClass("btn btn-primary small confirmreject").text("Reject"))
							)
						)
					)
			);*/

            div = $(storify.template.simpleModal(
                {
                    titlehtml:`Updates Needed`,
                    bodyhtml:`
                    <h3>Provide a reason why the submission is rejected so creator can make amendment according to you comments. (optional)</h3>
                    <textarea class="form-control" rows="4"></textarea>
                    `
                },
                "reject_submission",
                [   
                    {
                        label:"Cancel",
                        attr:{href:"#",class:"btn btn-primary small", "data-dismiss":"modal"}
                    },
                    {
                        label:"Reject",
                        attr:{href:"#",class:"btn btn-primary small confirmreject"}
                    }
                ]
            ));

            div.find(".actions .confirmreject").click(storify.brand.invitation_closed.dialog_click);

            $("body").append(div);
		}
        if( !$("#reject_reason").length ){

            /*
            $("body").append(
                $("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"reject_reason"})
                    .append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
                        .append($("<div>").addClass("modal-content")
                            .append($("<div>").addClass("modal-header")
                                .append($("<h5>").addClass("modal-title").text("Reject Reason"))
                                .append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal","aria-label":"Close"}).append($("<span>").attr({"aria-hidden":"hidden"}).html("&times;")))
                            )
                            .append($("<div>").addClass("modal-body deliverable_items")
                                .append($("<p>").addClass("reason"))
                            )
                            .append($("<div>").addClass("modal-footer")
                                .append($("<button>").addClass("btn btn-primary small").attr({"data-dismiss":"modal"}).text("Close"))
                            )
                        )
                    )
            );*/

            div = $(storify.template.simpleModal(
                {
                    titlehtml:`Updates Needed`,
                    bodyhtml:`
                    <p class="reason"></p>
                    `
                },
                "reject_reason",
                [   
                    {
                        label:"Close",
                        attr:{href:"#",class:"btn btn-primary small", "data-dismiss":"modal"}
                    }
                ]
            ));

            $("body").append(div);
        }
        if (!$("#downloadLinkModal").length) {
            /*
            $("body").append(
                $("<modal>").addClass("modal").attr({ tabindex: -1, role: "dialog", id: "downloadLinkModal" })
                .append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({ role: "document" })
                    .append($("<div>").addClass("modal-content")
                        .append($("<div>").addClass("modal-header")
                            .append($("<h5>").addClass("modal-title").text(""))
                            .append($("<button>").addClass("close").attr({ type: "button", "data-dismiss": "modal", "aria-label": "Close" }).append($("<span>").attr({ "aria-hidden": "true" }).html("&times")))
                        )
                        .append($("<div>").addClass("modal-body")
                            .append($("<a>").addClass("filename").attr({download:''}))
                            .append($("<div>").addClass("filesize"))
                            .append($("<div>").addClass("filemime"))
                        )
                        .append($("<div>").addClass("modal-footer")
                            .append(
                                $("<a>").addClass("btn btn-primary small download").text("download").attr({ href:"", download:''})
                            )
                        )
                    )
                )
            );
            */

             div = $(storify.template.simpleModal(
                {
                    titlehtml:``,
                    bodyhtml:`
                    <video width="640" height="480" controls="" style="width: 100%;height: 320px;">
                        <source src="" type="video/MP4">
                        Your browser does not support the video tag.
                    </video>
                    <img src="" style="width: 100%">
                    <div class="caption" style="padding:10px; background:#F6F8F8; white-space: pre-wrap; word-break: break-word;">
                    </div>
                    <a class="filename" download></a>
                    <div class="filesize"></div>
                    <div class="filemime"></div>
                    `
                },
                "downloadLinkModal",
                [   
                    {
                        label:"Download",
                        attr:{class:"btn btn-primary small download", href:"#", download:""}
                    }
                ]
            ));

            $("body").append(div);
        }
	},
    _gettingHistory:false,
    getHistory:function(deliverable_id, user_id, callback){
        if(storify.brand.deliverable_closed._gettingHistory) return;
        storify.brand.deliverable_closed._gettingHistory = true;
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{
                deliverable_id:deliverable_id,
                creator:user_id,
                method:"getDeliverableHistory"
            },
            error:function(request, status, error){
                storify.brand.deliverable_closed._gettingHistory = false;
            },
            success:function(rs){
                storify.brand.deliverable_closed._gettingHistory = false;
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
    	if(storify.brand.deliverable_closed._gettingDeliverable) return;
        storify.brand.deliverable_closed._gettingDeliverable = true;
        $.ajax({
            type:"POST",
            dataType:'json',
            data:{
                project_id:storify.project._project_id,
                method:"getDeliverable"
            },
            error:function(request, status, error){
                storify.brand.deliverable_closed._gettingDeliverable = false;
            },
            success:function(rs){
                storify.brand.deliverable_closed._gettingDeliverable = false;
                if(rs.error){
                    alert(rs.msg);
                }else{
                    storify.brand.deliverable_closed.display(rs, callback);
                }
            }
        });
    },
    createDeliverableItem:function(data){
    	storify.brand.deliverable_closed.addElementIfNotExist();
    	var d = $("<div>").addClass("deliverable-creator-item"),
            temp_creator = storify.project.user.getUserDetail(data.creator_id),
            temp_remark = null,
            temp_status = null,
            temp_action = null,
            temp_history = null;

        if(data.remark){
            temp_remark = $("<div>").addClass("single_block urldescription")
                                .append($("<p>").text(data.remark));
        }else{
            temp_remark = $("<div>").addClass("single_block urldescription")
                                .append($("<p>").append($("<i>").text("No caption entered.")));
        }
        if(data.status == "accepted" || data.status == "rejected"){
            if(data.status == "accepted"){
                temp_status = $("<div>").addClass("status_block")
                                .append($("<span>").attr({title:data.admin_remark}).addClass("item-status").text(data.status.charAt(0).toUpperCase() + data.status.slice(1)));
                d.addClass("item-accepted");
            }else{
                temp_status = $("<div>").addClass("status_block")
                                .append($("<a>").attr({href:"#", title:data.admin_remark}).addClass("item-status").text(data.status.charAt(0).toUpperCase() + data.status.slice(1))
                                    .click(function(e){
                                        e.preventDefault();
                                        var remark = $(this).attr("title");
                                        $("#reject_reason .reason").text(remark ? remark : "no reason given.");
                                        $("#reject_reason").modal("show");
                                    })
                                );
                d.addClass("item-rejected");
            }
        }else{
            /*
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
            */
        }
        if(data.history_id){
            temp_history = $("<a>").attr({href:"#"}).text("history")
                                .click(function(e){
                                    e.preventDefault();
                                    var _this = $(this);
                                    storify.brand.deliverable_closed.getHistory(data.deliverable_id, data.user_id, function(data2){
                                         $.each(data2.data, function(index,value){
                                            _this.parent().append(storify.brand.deliverable_closed.createHistoryBlock(value));
                                        });
                                        _this.remove();
                                    });
                                });
        }

        if(+data.file_id){

            var typeicon = "camera";
            switch(data.type){
                case "photo":
                    typeicon = "fa-camera";
                break;
                case "video":
                    typeicon = "fa-video-camera";
                break;
                case "extra":
                    typeicon = "fa-archive";
                    d.addClass("extra");
                break;
            }

            d.append($("<div>").addClass("top_panel")
                    .append($("<small>").text(data.submit_tt))
                    .append($("<div>").addClass("single_block")
                        .append($("<label>").append($("<span>").css({color:"#999","font-size":".9em"}).text("ASSET-"+storify.core.leadingZero(data.id, 9))).prepend($("<i>").addClass("fa "+typeicon).css({"margin-right":"5px"})))
                        .append($("<div>").addClass("file-container")
                            .append($("<div>").addClass("file-download-link").text(storify.brand.deliverable_closed.shortenFileName(data.filename)+" ("+data.mime+")")
                                            .append($("<i>").addClass("fa fa-arrow-circle-down").css({"margin-left":".5rem"}))
                                            .click(function(e){
                                                e.preventDefault();
                                                storify.brand.deliverable_closed._showDownloadDialog(data.file_id, data.remark ? data.remark : "No caption entered.");
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

            var typeicon = "camera";
            switch(data.type){
                case "photo":
                    typeicon = "fa-camera";
                break;
                case "video":
                    typeicon = "fa-video-camera";
                break;
                case "extra":
                    typeicon = "fa-archive";
                    d.addClass("extra");
                break;
            }
            
            d.append($("<div>").addClass("top_panel")
    	                .append($("<small>").text(data.submit_tt))
    	                .append($("<div>").addClass("single_block")
    	                    .append($("<label>").append($("<span>").css({color:"#999","font-size":".9em"}).text("ASSET-000000000".slice(0, -1*(data.id+"").length) + data.id)).prepend($("<i>").addClass("fa "+typeicon).css({"margin-right":"5px"})))
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
    _showDownloadDialog:function(id, caption){
        storify.loading.show();
        $("#downloadLinkModal").find("video")[0].pause();
        $("#downloadLinkModal").find("video source").remove();
        $("#downloadLinkModal").find("video").css({display:"none"});
        $("#downloadLinkModal").find("img").attr({src:""}).css({display:"none"});
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
                    var re = /(?:\.([^.]+))?$/;
                    var ext = re.exec(rs.filename);
                    if(ext[1] && ( $.inArray(ext[1].toLowerCase(), ["mp4", "m4a", "m4v", "f4v", "f4a", "m4b", "m4r", "f4b", "mov"]) != -1)){
                        $("#downloadLinkModal").find("video").prepend($("<source>").attr({src:rs.filelink}));
                        $("#downloadLinkModal").find("video").css({display:"block"});
                        $("#downloadLinkModal").find("video")[0].load();
                    }else if(ext[1] && ( $.inArray(ext[1].toLowerCase(), ["jpg","png","jpeg"]) != -1)){
                        $("#downloadLinkModal").find("img").attr({src:rs.filelink}).css({display:"block"});
                    }else{
                        
                    }

                    if(caption){
                        $("#downloadLinkModal").find(".caption").text(caption);
                    }else{
                        $("#downloadLinkModal").find(".caption").text("");
                    }

                    $("#downloadLinkModal").find(".filename")
                                                .attr({href:rs.filelink, download:rs.filename, target:"_blank"})
                                                .text(storify.brand.deliverable_closed.shortenFileName(rs.filename)+" ("+rs.filemime+")")
                                                .append($("<i>").addClass("fa fa-arrow-circle-down").css({"margin-left":".5rem"}));
                    $("#downloadLinkModal").find(".filesize").text("");
                    $("#downloadLinkModal").find(".filemime").text("");
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
                }else if(value2.type == "video"){
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

        photo_total = ( photo_total ) == 0 ? 0 : photo_total;
        video_total = ( video_total ) == 0 ? 0 : video_total;

        if(withData){
            /* <i class="fa fa-camera" style="margin-right: 5px;"></i> */
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
            	b.append(storify.brand.deliverable_closed.createDeliverableItem(value2));

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