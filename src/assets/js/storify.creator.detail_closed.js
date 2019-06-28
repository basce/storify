var storify = storify || {};
storify.creator = storify.creator || {};

storify.creator.detail_closed = {
    addElementIfNotExist:function(){
        /*
        if( !$("#detailModal").length ){
            $("body").append(
                $("<modal>").addClass("modal fullscreen").attr({tabindex:-1, role:"dialog", id:"detailModal"})
                    .append($("<div>").addClass("modal-dialog").attr({role:"document"})
                        .append($("<div>").addClass("modal-content")
                            .append($("<div>").addClass("modal-header")
                                .append($("<h5>").addClass("modal-title").text(""))
                                .append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal", "aria-label":"Close"}).append($("<span>").attr({"aria-hidden":"true"}).html("&times")))
                            )
                            .append($("<div>").addClass("modal-body")
                                .append($("<div>").addClass("container-fluid")
                                    .append($("<div>").addClass("row")
                                        .append($("<div>").addClass("col-md-6")
                                            .append($("<section>").addClass("section-container detailcontent").attr({id:"detailcontent"}))
                                        )
                                        .append($("<div>").addClass("col-md-6 actioncontent")
                                            .append($("<section>").addClass("section-container summarycontent").attr({id:"summarycontent"}))   
                                        )
                                    )
                                )
                            )
                            .append($("<div>").addClass("modal-footer"))
                        )
                    )
            );
        }
        */
        if( !$("#deliverableModal").length ){
            $("body").append(
                $("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"deliverableModal"})
                    .append($("<div>").addClass("modal-dialog modal-xl").attr({role:"document"})
                        .append($("<div>").addClass("modal-content")
                            .append($("<div>").addClass("modal-header")
                                .append($("<h5>").addClass("modal-title"))
                                .append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal", "aria-label":"Close"}).append($("<span>").attr({"aria-hidden":"true"}).html("&times")))
                            )
                            .append($("<div>").addClass("modal-body")
                                .append($("<div>").addClass("deliverable-groups"))
                            )
                            .append($("<div>").addClass("modal-footer"))
                        )
                    )
            );
        }
        if( !$("#newDetailModal").length ){
            $("body").append(
                $("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"newDetailModal"})
                    .append($("<div>").addClass("modal-dialog modal-dialog-centered modal-custom-xl").attr({role:"document"})
                        .append($("<div>").addClass("modal-content")
                            .append($("<div>").addClass("modal-header")
                                .append($("<h5>").addClass("modal-title").text(""))
                                .append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal", "aria-label":"Close"}).append($("<span>").attr({"aria-hidden":"true"}).html("&times")))
                            )
                            .append($("<div>").addClass("modal-body")
                                .append($("<ul>").addClass("nav nav-tabs").attr({role:"tablist", id:"tab_control"})
                                    .append($("<li>").addClass("nav-item")
                                        .append($("<a>").addClass("nav-link active").attr({id:"brief-tab","data-toggle":"tab",href:"#brief",role:"tab","aria-controls":"brief","aria-expanded":true}).text("Brief"))
                                    )
                                    .append($("<li>").addClass("nav-item")
                                        .append($("<a>").addClass("nav-link").attr({id:"bounty-tab","data-toggle":"tab",href:"#bounty",role:"tab","aria-controls":"bounty","aria-expanded":true}).text("Bounty"))
                                    )
                                    .append($("<li>").addClass("nav-item")
                                        .append($("<a>").addClass("nav-link").attr({id:"submission-tab","data-toggle":"tab",href:"#submission",role:"tab","aria-controls":"submission","aria-expanded":true}).text("Submission"))
                                    )
                                    .append($("<li>").addClass("nav-item")
                                        .append($("<a>").addClass("nav-link").attr({id:"final-tab","data-toggle":"tab",href:"#final",role:"tab","aria-controls":"final","aria-expanded":true}).text("Final"))
                                    )
                                )
                                .append($("<div>").addClass("tab-content").attr({id:"tab_content"})
                                    .append($("<div>").addClass("tab-pane fade show active").attr({id:"brief",role:"tabpanel","aria-labelledby":"brief-tab"})
                                        .append($("<div>").addClass("detailcontent").attr({id:"brief-content"}))
                                    )
                                    .append($("<div>").addClass("tab-pane fade").attr({id:"bounty",role:"tabpanel","aria-labelledby":"bounty-tab"})
                                        .append($("<div>").addClass("bountycontent").attr({id:"bounty-content"}))
                                    )
                                    .append($("<div>").addClass("tab-pane fade").attr({id:"submission",role:"submissionpanel","aria-labelledby":"submission-tab"})
                                        .append($("<div>").addClass("submissioncontent").attr({id:"submission-content"})
                                            .append($("<div>").addClass("submissionlist")
                                                .append($("<ul>").addClass("nav nav-tabs").attr({role:"tablist", id:"tab_control2"})
                                                    .append($("<li>").addClass("nav-item")
                                                        .append($("<a>").addClass("nav-link active").attr({id:"photo-tab","data-toggle":"tab",href:"#photolist",role:"tab","aria-controls":"photolist","aria-expanded":true})
                                                                    .append($("<span>").text("99/100"))
                                                                    .append(document.createTextNode(" "))
                                                                    .append($("<i>").addClass("fa fa-camera"))
                                                            )
                                                    )
                                                    .append($("<li>").addClass("nav-item")
                                                        .append($("<a>").addClass("nav-link").attr({id:"video-tab","data-toggle":"tab",href:"#videolist",role:"tab","aria-controls":"videolist","aria-expanded":true})
                                                                    .append($("<span>").text("100/100"))
                                                                    .append(document.createTextNode(" "))
                                                                    .append($("<i>").addClass("fa fa-video-camera"))
                                                            )
                                                    )
                                                )
                                                .append($("<div>").addClass("tab-content").attr({id:"tab_content2"})
                                                   .append($("<div>").addClass("tab-pane fade show active").attr({id:"photolist",role:"tabpanel","aria-labelledby":"photolist-tab"})
                                                        .append($("<div>").addClass("list photolist"))
                                                    )
                                                    .append($("<div>").addClass("tab-pane fade").attr({id:"videolist",role:"tabpanel","aria-labelledby":"videolist-tab"})
                                                        .append($("<div>").addClass("list videolist"))
                                                    ) 
                                                )
                                            )
                                        )
                                    )
                                    .append($("<div>").addClass("tab-pane fade").attr({id:"final", role:"finalpanel","aria-labelledby":"final-tab"})
                                        .append($("<div>").addClass("finalcontent").attr({id:"final-content"})
                                        )
                                    )
                                )
                            )
                            .append($("<div>").addClass("modal-footer"))
                        )
                    )
            );

            $('#tab_control2 a[data-toggle="tab"]').on('shown.bs.tab',function(e){
                //change
                var target = $(e.target).attr("href");

                if(target == "#photolist"){
                    storify.creator.detail_closed.viewlist("photo");
                }else{
                    storify.creator.detail_closed.viewlist("video");
                }
            });
        }   
        if( !$("#rejectModal").length ){
            $("body").append(
                $("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"rejectModal"})
                    .append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
                        .append($("<div>").addClass("modal-content")
                            .append($("<div>").addClass("modal-header")
                                .append($("<h5>").addClass("modal-title").text(""))
                                .append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal", "aria-label":"Close"}).append($("<span>").attr({"aria-hidden":"true"}).html("&times")))
                            )
                            .append($("<div>").addClass("modal-body")
                                
                            )
                            .append($("<div>").addClass("modal-footer")
                                .append(
                                    $("<button>").addClass("btn btn-primary small").text("Ok").attr({type:"button","data-dismiss":"modal", "aria-label":"Close"})
                                )
                            )
                        )
                    )
            );
        }
        if (!$("#downloadLinkModal").length) {
            $("body").append(
                $("<modal>").addClass("modal").attr({ tabindex: -1, role: "dialog", id: "downloadLinkModal" })
                .append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({ role: "document" })
                    .append($("<div>").addClass("modal-content")
                        .append($("<div>").addClass("modal-header")
                            .append($("<h5>").addClass("modal-title").text(""))
                            .append($("<button>").addClass("close").attr({ type: "button", "data-dismiss": "modal", "aria-label": "Close" }).append($("<span>").attr({ "aria-hidden": "true" }).html("&times")))
                        )
                        .append($("<div>").addClass("modal-body")
                            .append($("<a>").addClass("filename"))
                            .append($("<div>").addClass("filesize"))
                            .append($("<div>").addClass("filemime"))
                        )
                        .append($("<div>").addClass("modal-footer")
                            .append(
                                $("<a>").addClass("btn btn-primary small download").text("download").attr({ target:"_blank", href:""})
                            )
                        )
                    )
                )
            );
        }
    },
    createBountyTable:function(detail){
        var cashtable = $("<div>").addClass("bountycash2"),
            gifttable = null;

        var totalcash = 0;

        if(detail.bounty_type == "gift"){
            detail.cost_per_photo = 0;
            detail.cost_per_video = 0;
        }

        totalcash = detail.no_of_photo * detail.cost_per_photo + detail.no_of_video * detail.cost_per_video;

        cashtable.append($("<div>").addClass("bountyrow")
                .append($("<h2>")
                .append($("<span>").text(detail.no_of_photo)
                            .append($("<i>").addClass("fa fa-camera"))
                    )
                .append($("<span>").text("S$"+storify.project.formatMoney(detail.cost_per_photo)+" each"))
            ));
        cashtable.append($("<div>").addClass("bountyrow")
                .append($("<h2>")
                .append($("<span>").text(detail.no_of_video)
                            .append($("<i>").addClass("fa fa-video-camera"))
                    )
                .append($("<span>").text("S$"+storify.project.formatMoney(detail.cost_per_video)+" each"))
            ));

        if(detail.bounty_type == "both" || detail.bounty_type == "gift"){
            gifttable = $("<div>").addClass("bountygift2");
            gifttable.append($("<div>").addClass("bountyrow giftrow")
                .append($("<h2>")
                .append($("<span>").text("1")
                            .append($("<i>").addClass("fa fa-gift"))
                    )
                .append($("<span>").text(detail.reward_name)))
            );
        }

        var div = $("<div>");
        div.append($("<h5>").text("Do a good job and you will be well-rewarded."))
            .append(cashtable)
            .append(gifttable)
            .append($("<h5>").text("In total, you will receive S$"+storify.project.formatMoney(totalcash)+", and entitlements, if any."));
        $("#bounty-content").empty()
            .append(div);
    },
    _gettingHistory:false,
    getHistory:function(deliverable_id, user_id, callback){
        if(storify.creator.detail_closed._gettingHistory) return;
        storify.creator.detail_closed._gettingHistory = true;
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{
                deliverable_id:deliverable_id,
                user_id:user_id,
                method:"getDeliverableHistory"
            },
            error:function(request, status, error){
                storify.creator.detail_closed._gettingHistory = false;
            },
            success:function(rs){
                storify.creator.detail_closed._gettingHistory = false;
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
    createDeliverableItem:function(data){
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
        
        temp_status = $("<div>").addClass("single_block")
                            .append($("<label>").text("Status"))
                            .append($("<span>").addClass("item-status").text(data.response_status))
                            .append($("<small>").text(data.response_date));
        d.addClass("item-accepted");

        if(data.history_id){
            temp_history = $("<a>").attr({href:"#"}).text("history")
                                .click(function(e){
                                    e.preventDefault();
                                    var _this = $(this);
                                    storify.creator.detail_closed.getHistory(data.deliverable_id, data.user_id, function(data2){
                                         $.each(data2.data, function(index,value){
                                            _this.parent().append(storify.creator.detail_closed.createHistoryBlock(value));
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
            b.append(storify.creator.detail_closed.createDeliverableItem(value));

            a.append(b);
            a.append(c);
            $(".deliverable-groups").append(a);
        });

        if( typeof callback === "function" ){
            callback();
        }
    },
    _gettingDeliverable:false,
    getDeliverables:function(user_id, callback){
        if(storify.creator.detail_closed._gettingDeliverable) return;
        storify.creator.detail_closed._gettingDeliverable = true;
        $.ajax({
            type: "POST",
            dataType: "json",
            data:{
                project_id:storify.project._project_id,
                user_id:user_id,
                method:"getDeliverable"
            },
            error:function(request, status, error){
                storify.creator.detail_closed._gettingDeliverable = false;
            },
            success:function(rs){
                storify.creator.detail_closed._gettingDeliverable = false;  
                if(rs.error){
                    alert(rs.msg);
                }else{
                    storify.creator.detail_closed.display(rs.data, callback);
                }
            }
        });
    },
    createSummary:function(detail, submission){
        var bountygift = $("<div>").addClass("bountyrow"),
            bountycash = $("<div>").addClass("bountyrow"),
            cost_per_video,
            cost_per_photo,
            totalcash,
            no_of_photo,
            no_of_video;
        
        if(detail.bounty_type == "gift"){
            cost_per_video = 0;
            cost_per_photo = 0;
        }else{
            cost_per_video = detail.cost_per_video;
            cost_per_photo = detail.cost_per_photo;
        }

        totalcash = 0;
        no_of_photo = 0;
        no_of_video = 0;

        $.each(submission, function(index,value){
            if(value.status == "accepted"){
                if(value.type == "photo"){
                    no_of_photo++;
                }else if(value.type == "video"){
                    no_of_video++;
                }
            }
        });
        if(+no_of_photo){
            totalcash += +no_of_photo*cost_per_photo;
        }

        if(+no_of_video){
            totalcash += +no_of_video*cost_per_video;
        }

        bountycash.append(//<i class="fa fa-money" aria-hidden="true"></i>
            $("<h2>").append($("<i>").addClass("fa fa-money").attr({"aria-hidden":true}))
                .append(document.createTextNode("S$"+storify.project.formatMoney(totalcash)))
            );
        
        bountygift = null;

        if(detail.bounty_type == "both" || detail.bounty_type == "gift"){
            if(no_of_photo + no_of_video){
                bountygift = $("<div>").addClass("bountyrow")
                                .append($("<h2>")
                                    .append($("<i>").addClass("fa fa-gift").attr({"aria-hidden":true}))
                                    .append(document.createTextNode(detail.reward_name))
                            );
            }
        }

        $("#final-content").empty()
            .append($("<div>").addClass("bountycash2")
                .append($("<h5>").text("The project has closed, and here are your rewards."))
                .append(bountycash)
                .append(bountygift)
                .append($("<hr>"))
                .append($("<h5>").html('You have delivered '+no_of_photo+' <i class="fa fa-camera"></i> and '+no_of_video+' <i class="fa fa-video-camera"></i> .'))
            );
    },
    /*
    createSummary:function(data){
        var items = $("<div>").addClass("items");

        $.each(data.deliverables, function(index,value){
            var icon;
            if(value.type == "photo"){
                icon = $("<i>").addClass("fa fa-camera");
            }else{
                icon = $("<i>").addClass("fa fa-video-camera");
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

        if(data.deliverables.length){
            items.append($("<div>").addClass("item iconcenter").append($("<a>").attr({href:"#"}).append($("<i>").addClass("fa fa-search-plus")).click(function(e){
                e.preventDefault();
                e.stopPropagation(); 
                storify.creator.detail_closed.getDeliverables(data.user_id, function(){
                    $("#deliverableModal").modal("show");
                });
            })));
        }

        //build breakdown_table
        var breakdown_table = $("<div>").addClass("breakdown_table"),
            breakdown_items = $("<div>").addClass("breakdown_items");

        $.each(data.breakdown, function(index, value){
            var icon;
            if(value.type == "photo"){
                icon = $("<i>").addClass("fa fa-camera");
            }else{
                icon = $("<i>").addClass("fa fa-video-camera");
            }
            breakdown_items.append($("<div>").addClass("breakdown_row")
                    .append($("<div>").addClass("breakdown_item_left")
                            .append(icon)
                            .append(document.createTextNode(" × "+value.amount+" "))
                            .append($("<small>").text("out of "+value.expected))
                        )
                    .append($("<div>").addClass("breakdown_item_right")
                            .append("$"+value.amount*value.cost_per_item)
                        )
                    );
        });
        breakdown_table.append(breakdown_items);

        //build summary
        var summary_cont = $("<div>").addClass("breakdown_summary");

        summary_cont.append($("<div>").addClass("summary_item")
                .text("$"+data.total_cash)
            );

        if(data.gift){
            summary_cont.append($("<div>").addClass("summary_item")
                .append(document.createTextNode(data.gift))
                .append($("<small>").text("(Gift)"))
            );
        }

        breakdown_table.append(summary_cont);

        $("#final-content").empty().append($("<h1>").text("Summary"))
            .append(items)
            .append(
                $("<div>").addClass("description_block")
                    .append($("<div>").addClass("single_row")
                        .append($("<label>").text("Completion"))
                        .append(document.createTextNode(parseInt(data.completion*100,10)+"%"))
                    )
            )
            .append($("<h2>").text("Breakdown"))
            .append(breakdown_table);
    },
    */
    _gettingCompletionSummary:false,
    getCompletionSummary:function(project_id, callback){
        if(storify.creator.detail_closed._gettingCompletionSummary) return;
        storify.creator.detail_closed._gettingCompletionSummary = true;
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "getCreatorCompletion",
                project_id: project_id
            },
            success:function(rs){
                storify.creator.detail_closed._gettingCompletionSummary = false;
                if(callback) callback(rs);
            }
        });
    },
    _gettingDetail:false,
    viewDetail:function(project_id){
        if(storify.creator.detail_closed._gettingDetail) return;
        storify.creator.detail_closed._gettingDetail = true;
        storify.creator.detail_closed.addElementIfNotExist();
        storify.loading.show();
        $("#newDetailModal .nav-tabs").each(function(index,value){
          $(value).find("a:eq(0)").tab("show");
        });
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "getDetail",
                project_id: project_id
            },
            success:function(rs){
                storify.creator.detail_closed._gettingDetail = false;
                if(rs.error){
                    storify.loading.hide();
                }else{
                    _project_id = project_id;
                    storify.project._project_id = project_id;
                    storify.project.user.getAllUser(function(){
                        storify.loading.hide();
                        storify.creator.detail_closed.createDetail(rs.data);
                        storify.creator.detail_closed.getSubmission(null, function(xdata){
                            storify.creator.detail_closed.createSummary(rs.data.detail, xdata)
                        });
                        $("#newDetailModal").modal();
                    });
                }
            }
        });
    },
    createSubmissionBlock:function(data){
        var iconClass,
            mainClass,
            div,
            actiondiv = $("<div>").addClass("urlaction");

        if(data.type == "photo"){
            iconClass = "fa-camera";
            mainClass = "photo";
        }else{
            iconClass = "fa-video-camera";
            mainClass = "video";
        }

        switch(data.status){
            case "accepted":
                actiondiv.append($("<span>").addClass("accept")
                                .append($("<i>").addClass("fa fa-thumbs-up").attr({"aria-hidden":true}))
                            );
            break;
            case "rejected":
                //pending
                actiondiv
                /*.append($("<a>").addClass("bin").attr({href:"#"})
                        .append($("<i>").addClass("fa fa-trash-o").attr({"aria-hidden":true}))
                        .click(function(e){
                            e.preventDefault();
                            storify.creator.detail.removeSubmission(data.id, data.type);
                        })
                    )*/
                    .append($("<a>").addClass("reject").attr({href:"#"})
                        .append($("<i>").addClass("fa fa-thumbs-down").attr({"aria-hidden":true}))
                        .click(function(e){
                            e.preventDefault();
                            $("#rejectModal .modal-body").empty()
                                .append($("<p>").text(data.admin_remark?data.admin_remark:"no reason given"));
                            $("#rejectModal").modal("show");
                        })
                    );
            break;
            default:
                //pending
                actiondiv.append($("<a>").addClass("bin").attr({href:"#"})
                        .append($("<i>").addClass("fa fa-trash-o").attr({"aria-hidden":true}))
                        .click(function(e){
                            e.preventDefault();
                            storify.creator.detail.removeSubmission(data.id, data.type);
                        })
                    );
            break;
        }

        if(+data.file_id){
            div = $("<div>").addClass("submission " + mainClass).attr({ o: data.id })
                .append($("<i>").addClass("submission-icon fa " + iconClass))
                .append($("<div>").addClass("urlrow")
                    .append($("<div>").addClass("urlinput")
                        .append($("<div>").addClass("file-container")
                            .append($("<div>").addClass("file-download-link").text(storify.creator.detail_closed.shortenFileName(data.filename)+" ("+data.mime+")")
                                            .append($("<i>").addClass("fa fa-arrow-circle-down").css({"margin-left":".5rem"}))
                                            .click(function(e){
                                                e.preventDefault();
                                                storify.creator.detail_closed._showDownloadDialog(data.file_id);
                                            })
                                )
                        )
                    )
                    .append(actiondiv)
                )
                .append($("<div>").addClass("urldescription")
                    .append($("<p>").text(data.remark ? data.remark : "no caption given."))
                );
        }else{
            div = $("<div>").addClass("submission "+mainClass).attr({o:data.id})
                .append($("<i>").addClass("submission-icon fa fa-camera"))
                    .append($("<div>").addClass("urlrow")
                        .append($("<div>").addClass("urlinput")
                            .append($("<input>").addClass("form-control").attr({readonly:true}).val(data.URL))
                        )
                        .append(actiondiv)
                    )
                    .append($("<div>").addClass("urldescription")
                        .append($("<p>").text(data.remark ? data.remark : "no caption given."))
                    );
        }
        return div;
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
                                                .attr({href:rs.filelink, target:"_blank"})
                                                .text(storify.creator.detail_closed.shortenFileName(rs.filename)+" ("+rs.filemime+")")
                                                .append($("<i>").addClass("fa fa-arrow-circle-down").css({"margin-left":".5rem"}));
                    $("#downloadLinkModal").find(".filesize").text("");
                    $("#downloadLinkModal").find(".filemime").text();
                    $("#downloadLinkModal").find(".download").attr({href:rs.filelink})
                    $("#downloadLinkModal").modal("show");
                }
            }

        })
    },
    updatelistnotification:function(type){
        
    },
    viewlist:function(type){
        var max_photo = $("#tab_control2").attr("m_photo") ? +$("#tab_control2").attr("m_photo") : 0,
            max_video = $("#tab_control2").attr("m_video") ? +$("#tab_control2").attr("m_video") : 0,
            n_photo = $("#tab_control2").attr("n_photo") ? +$("#tab_control2").attr("n_photo") : 0,
            n_video = $("#tab_control2").attr("n_video") ? +$("#tab_control2").attr("n_video") : 0;

        if(type == "photo"){
            if(n_photo < max_photo){
                //still available
                $(".form_submit_btn").removeClass("disabled");
            }else{
                $(".form_submit_btn").addClass("disabled");
            }
        }else{
            if(n_video < max_video){
                $(".form_submit_btn").removeClass("disabled");
            }else{
                $(".form_submit_btn").addClass("disabled");
            }
        }

        //update text
        $("#photo-tab span").text(n_photo+"/"+max_photo);
        $("#video-tab span").text(n_video+"/"+max_video);
    },
    listSubmissions:function(no_p, no_v, data, viewtype){
        $("#submission-content .photolist").empty();
        $("#submission-content .videolist").empty();

        var number_photo = 0,
            number_video = 0,
            total_assets = no_p + no_v,
            number_submitted = 0,
            number_finalised = 0,
            asset_label = "";
        $.each(data, function(index,value){
            if(value.type == "photo"){
                number_photo++;
                $("#submission-content .photolist").append(storify.creator.detail_closed.createSubmissionBlock(value));
            }else if(value.type == "video"){
                number_video++;
                $("#submission-content .videolist").append(storify.creator.detail_closed.createSubmissionBlock(value));
            }
            if(value.status == "accepted"){
                number_finalised++;
            }
            number_submitted++;
        });

        if(number_photo == 0){
            $("#submission-content .photolist").append($("<p>").text("The project has ended. No submissions have been made."));
        }

        if(number_video == 0){
            $("#submission-content .videolist").append($("<p>").text("The project has ended. No submissions have been made."));
        }

        $("#tab_control2").attr({
            "m_photo":no_p,
            "m_video":no_v,
            "n_photo":number_photo,
            "n_video":number_video
        });

        //submission_title
        //1 / 3 assets submitted and 0 finalised
        if(number_submitted == 1){
            asset_label = "asset";
        }else{
            asset_label = "assets";
        }

        $("#submission-content .submission_title").text(number_submitted+" / "+total_assets+" "+asset_label+" and "+number_finalised+" finalised");

        if(viewtype == "photo"){
            $('#tab_control2 a[href="#photolist"]').tab("show");
        }else{
            $('#tab_control2 a[href="#videolist"]').tab("show");
        }
        storify.creator.detail_closed.viewlist(viewtype);
    },
    gettingSubmissions:false,
    getSubmission:function(viewtype, onComplete){
        if(storify.creator.detail_closed.gettingSubmissions) return;
        storify.creator.detail_closed.gettingSubmissions = true;
        storify.loading.show();
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "getSubmissions",
                project_id: storify.project._project_id
            },
            success:function(rs){
                storify.creator.detail_closed.gettingSubmissions = false;
                storify.loading.hide();
                if(rs.error){
                   
                }else{
                    if(!viewtype){
                        //if viewtype is not set
                        if(rs.data.length){
                            var number_photo = 0,
                                number_video = 0;
                            $.each(rs.data, function(index,value){
                                if(value.type == "photo"){
                                    number_photo++;
                                }else{
                                    number_video++;
                                }
                            });

                            if(!parseInt(rs.no_of_photo,10)){
                                viewtype = "video";
                            }else if(parseInt(rs.no_of_photo,10) < number_photo){
                                viewtype = "photo";
                            }else if(!parseInt(rs.no_of_video,10)){
                                viewtype = "photo";
                            }else if(parseInt(rs.no_of_video,10) < number_video){
                                viewtype = "video";
                            }else{
                                viewtype = "photo";
                            }
                        }else{
                            viewtype = "photo";
                        }
                    }
                    storify.creator.detail_closed.listSubmissions(parseInt(rs.no_of_photo,10), parseInt(rs.no_of_video,10), rs.data, viewtype);
                    if(onComplete){
                        onComplete(rs.data);
                    }
                }
            }
        });
    },
    createDetail:function(data){
        //bramd
        var brandtext = [];
        if(data.summary.brand && data.summary.brand.length){
            $.each(data.summary.brand, function(index, value){
                brandtext.push(value.name);
            });
        }

        //locations
        var locationtext = [];
        if(data.summary.location && data.summary.location.length){
            $.each(data.summary.location, function(index, value){
                locationtext.push(value.name);
            });
        }

        //tags
        /*
        var tagstext = [];
        if(data.summary.tag && data.summary.tag.length){
            $.each(data.summary.tag, function(index, value){
                tagstext.push(value.name);
            });   
        }
        */

        var deliverable_block = $("<div>").addClass("deliverable");
        if(+data.detail.no_of_photo && +data.detail.no_of_video){
            deliverable_block
                .append(document.createTextNode(data.detail.no_of_photo+" "))
                .append($("<i>").addClass("fa fa-camera"))
                .append(document.createTextNode(" | "+data.detail.no_of_video+" "))
                .append($("<i>").addClass("fa fa-video-camera"));
        }else if(+data.detail.no_of_photo){
            deliverable_block
                .append(document.createTextNode(data.detail.no_of_photo+" "))
                .append($("<i>").addClass("fa fa-camera"));
        }else if(+data.detail.no_of_video){
            deliverable_block
                .append(document.createTextNode(data.detail.no_of_video+" "))
                .append($("<i>").addClass("fa fa-video-camera"));
        }

        //sample block
        var owlImages = null;

        if(data.sample.length){
            owlImages = $("<div>").addClass("samples_cont owl-carousel owl-theme");

            $.each(data.sample, function(index, value){
                owlImages.append(
                    $("<a>").addClass("sample_clickable")
                        .attr({href:value.URL, target:"_blank"})
                        .css({"background-image":"url("+value.URL+")"})
                    );
            });
        }

        $("#bounty-content").empty();
        storify.creator.detail_closed.createBountyTable(data.detail);

        var cont = $("#brief-content");
        cont.empty();

        cont.append(
                $("<div>").addClass("project_header")
                    .append($("<div>").addClass("brand").text(brandtext.join(", ")))
                    .append($("<h2>").text(data.name))
                    .append($("<div>").addClass("location").text(locationtext.join(", ")))
                    .append($("<div>").addClass("date_cont")
                        .append($("<div>").addClass("text-right")
                                .append($("<i>").addClass("fa fa-calendar-o"))
                                .append(document.createTextNode(" Accept "+data.summary.formatted_invitation_closing_date))
                            )
                        .append($("<div>").addClass("text-right")
                                .append($("<i>").addClass("fa fa-calendar-o"))
                                .append(document.createTextNode(" Deliver "+data.summary.formatted_closing_date))
                            )
                    )
            )
            .append(deliverable_block)
            .append($("<div>").addClass("linkify").html(data.detail.description_brief))
            .append($("<div>").addClass("linkify").html(data.detail.deliverable_brief))
            ;
        cont.append(owlImages);

        var _interval = setInterval(function(args) {
            // body
            if(owlImages && owlImages.parent().is(":visible")){
                owlImages.owlCarousel({
                    loop:false,
                    autoplay:true,
                    margin:10,
                    responsiveClass:true,
                    responsiveBaseElement:"#brief-content",
                    responsive:{
                        0:{
                            items:1,
                            nav:true
                        },
                        250:{
                            items:2,
                            nav:true
                        },
                        650:{
                            items:3,
                            nav:true
                        },
                        1000:{
                            items:5,
                            nav:true
                        }
                    }
                });
                clearInterval(_interval);
            }
        }, 50);
        $(".linkify").linkify({
            target: "_blank"
        });
        return cont;
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