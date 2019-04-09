var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.detail_closed = {
    addElementIfNotExist:function(){
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
                                            .append($("<section>").addClass("section-container summarycontent").attr({id:"summarycontent"})
                                                .append($("<h1>").text("Summary"))
                                                .append($("<div>").addClass("creators"))
                                                .append($("<div>").addClass("summary"))
                                                )   
                                        )
                                    )
                                )
                            )
                            .append($("<div>").addClass("modal-footer"))
                        )
                    )
            );
        }     
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
    },
    createCreator:function(data){
        var items = $("<div>").addClass("items"),
            compact, detail;

        compact = $("<div>").addClass("compact")
                        .append($("<div>").addClass("breakdown_row")
                                    .append($("<div>").addClass("breakdown_item_left")
                                        .append($("<div>").addClass("single_row")
                                                    .append($("<label>").text("Completion"))
                                                    .append(document.createTextNode(parseInt(data.completion*100,10)+"%"))
                                            )
                                        )
                                    .append($("<div>").addClass("breakdown_item_right")
                                                .text("$"+data.total_cash)
                                        )
                            );

        $.each(data.deliverables, function(index,value){
            var icon;
            if(value.type == "photo"){
                icon = $("<i>").addClass("fa fa-file-image-o");
            }else{
                icon = $("<i>").addClass("fa fa-file-video-o");
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
                storify.brand.detail_closed.getDeliverables(data.user_id, function(){
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
                icon = $("<i>").addClass("fa fa-file-image-o");
            }else{
                icon = $("<i>").addClass("fa fa-file-video-o");
            }
            breakdown_items.append($("<div>").addClass("breakdown_row")
                    .append($("<div>").addClass("breakdown_item_left")
                            .append(icon)
                            .append(document.createTextNode(" × "+value.amount+" "))
                            .append($("<small>").text("out of "+value.expected))
                        )
                    .append($("<div>").addClass("breakdown_item_right")
                            .append("$"+ value.amount*value.cost_per_item)
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

        detail = $("<div>").addClass("detail")
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

        var tempUserObj = storify.project.user.getUserDetail(data.user_id)

        return $("<div>").addClass("creator")
                    .append($("<div>").addClass("creator_left")
                                .append($("<div>").addClass("profile-image").css({"background-image":"url("+tempUserObj.profile_image+")"}))
                        )
                    .append($("<div>").addClass("creator_right")
                        .append(compact)
                        .append(detail)
                    )
                    .click(function(e){
                                e.preventDefault();
                                $(this).toggleClass("expand");
                            });
    },
    _gettingHistory:false,
    getHistory:function(deliverable_id, user_id, callback){
        if(storify.brand.detail_closed._gettingHistory) return;
        storify.brand.detail_closed._gettingHistory = true;
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{
                deliverable_id:deliverable_id,
                user_id:user_id,
                method:"getDeliverableHistory"
            },
            error:function(request, status, error){
                storify.brand.detail_closed._gettingHistory = false;
            },
            success:function(rs){
                storify.brand.detail_closed._gettingHistory = false;
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

        if(data.remark){
            temp_remark = $("<div>").addClass("single_block")
                                .append($("<label>").text("Remark"))
                                .append(document.createTextNode(data.remark));
        }
        
        temp_status = $("<div>").addClass("single_block")
                            .append($("<label>").text("Status"))
                            .append($("<span>").addClass("item-status").text(data.status))
                            .append($("<small>").text(data.admin_response_tt));

        d.addClass("item-accepted");

        if(data.URL){
            d.append($("<div>").addClass("top_panel")
                .append($("<div>").addClass("single_block")
                    .append($("<label>").text("Submission").prepend($("<i>").addClass("fa fa-file-"+data.type+"-o").css({"margin-right":"5px"})))
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

            a.append($("<h3>").text(tempname));
            if(value.deliverable_remark){
                a.append($("<div>").addClass("deliverable-remark").text(value.deliverable_remark));
            }
            b.append(storify.brand.detail_closed.createDeliverableItem(value));

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
        if(storify.brand.detail_closed._gettingDeliverable) return;
        storify.brand.detail_closed._gettingDeliverable = true;
        $.ajax({
            type: "POST",
            dataType: "json",
            data:{
                project_id:storify.project._project_id,
                user_id:user_id,
                method:"getDeliverable"
            },
            error:function(request, status, error){
                storify.brand.detail_closed._gettingDeliverable = false;
            },
            success:function(rs){
                storify.brand.detail_closed._gettingDeliverable = false;  
                if(rs.error){
                    alert(rs.msg);
                }else{
                    storify.brand.detail_closed.display(rs.data, callback);
                }
            }
        });
    },
    createSummary:function(data){
        $(".creators").empty();

        $.each(data.creators, function(index,value){
            $(".creators").append(storify.brand.detail_closed.createCreator(value));
        });

        $(".summary").empty();

        if(+data.total_cash){
            $(".summary").append($("<div>").addClass("summary_item").text("$"+data.total_cash));
        }

        if(data.total_gift && data.total_gift.amount && data.total_gift.name){
            $(".summary").append(document.createTextNode(data.total_gift.name+" "))
                         .append($("<small>").text(" × "+data.total_gift.amount+" "));
        }
    },
    _gettingCompletionSummary:false,
    getCompletionSummary:function(project_id, callback){
        if(storify.brand.detail_closed._gettingCompletionSummary) return;
        storify.brand.detail_closed._gettingCompletionSummary = true;
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "getBrandCompletion",
                project_id: project_id
            },
            success:function(rs){
                storify.brand.detail_closed._gettingCompletionSummary = false;
                if(rs.error){
                    console.log(rs);
                }else{
                    if(callback) callback(rs);
                }
            }
        })
    },
    _gettingDetail:false,
    viewDetail:function(project_id){
        if(storify.brand.detail_closed._gettingDetail) return;
        storify.brand.detail_closed._gettingDetail = true;
        storify.brand.detail_closed.addElementIfNotExist();
        storify.loading.show();
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "getDetail",
                project_id: project_id
            },
            success:function(rs){
                storify.brand.detail_closed._gettingDetail = false;
                if(rs.error){
                    storify.loading.hide();
                }else{
                    _project_id = project_id;
                    storify.project._project_id = project_id;
                    storify.project.user.getAllUser(function(){
                        storify.loading.hide();
                        storify.brand.detail_closed.createDetail(rs.data);
                        $("#detailModal").modal();
                        storify.brand.detail_closed.getCompletionSummary(project_id, function(rs2){
                            storify.brand.detail_closed.createSummary(rs2.data);
                            $("#detailModal").modal();
                        });
                    });
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
        var tagstext = [];
        if(data.summary.tag && data.summary.tag.length){
            $.each(data.summary.tag, function(index, value){
                tagstext.push(value.name);
            });   
        }

        //deliverable detail
        var deliverable_ar = [], photocount = 1, videocount = 1;
        $.each(data.delivery, function(index,value){
            if(value.remark){
                if(value.type == "photo"){
                    deliverable_ar.push([
                        "photo #" + photocount,
                        value.remark
                        ]);
                    photocount++;
                }
                if(value.type == "video"){
                    deliverable_ar.push([
                        "video #" + photocount,
                        value.remark
                        ]);
                    photocount++;
                }
            }
        });

        var deliverable_block =$("<div>").addClass("description_block")
                    .append($("<h2>").text("Deliverables"))
                    .append($("<p>").text(data.summary.deliverables)
                                    .append(data.detail.no_of_video != "0" && data.detail.video_length ? document.createTextNode(" ("+data.detail.video_length+"s)") : null)
                        );

        if(deliverable_ar.length){
            deliverable_block.append($("<h3>").text("Details"));

            $.each(deliverable_ar, function(index, value){
                deliverable_block.append($("<div>").addClass("single_row")
                                            .append($("<label>").text(value[0]))
                                            .append(document.createTextNode(value[1]))
                    );
            });
        }

        //sample block
        var sample_block = null, sample_inner_block = [];

        if(data.sample.length){
            sample_block = $("<div>").addClass("description_block")
                                .append($("<h2>").text("Samples"));;

            sample_inner_block = $("<div>").addClass("sample_groups");

            $.each(data.sample, function(index, value){
                sample_inner_block.append(
                    $("<a>").addClass("sample_clickable")
                        .attr({href:value.URL, target:"_blank"})
                        .css({"background-image":"url("+value.URL+")"})
                    );
            });

            sample_block.append(sample_inner_block);
        }

        var bounty_block = null, bounty_ul;
        bounty_block = $("<div>").addClass("description_block");
        bounty_block.append($("<h2>").text("Bounty"));

        bounty_ul = $("<ul>");
        if(data.detail.bounty_type == "both"){
            bounty_ul.append(
                $("<li>").append($("<label>").text("Cash"))
                        .append(document.createTextNode("$"+data.summary.bounty[0].value+" ( $"+data.detail.cost_per_photo+" for each "))
                        .append($("<i>").addClass("fa fa-file-image-o"))
                        .append(document.createTextNode(", $"+data.detail.cost_per_video+" for each "))
                        .append($("<i>").addClass("fa fa-file-video-o"))
                        .append(document.createTextNode(" )"))
            );

            bounty_ul.append(
                $("<li>").append($("<label>").text("Gift"))
                        .append(document.createTextNode(data.summary.bounty[1].value))
            );
        }else if(data.detail.bounty_type == "cash"){
            bounty_ul.append(
                $("<li>").append($("<label>").text("Cash"))
                        .append(document.createTextNode("$"+data.summary.bounty[0].value+" ( $"+data.detail.cost_per_photo+" for each "))
                        .append($("<i>").addClass("fa fa-file-image-o"))
                        .append(document.createTextNode(", $"+data.detail.cost_per_video+" for each "))
                        .append($("<i>").addClass("fa fa-file-video-o"))
                        .append(document.createTextNode(" )"))
            );
        }else{
            bounty_ul.append(
                $("<li>").append($("<label>").text("Gift"))
                        .append(document.createTextNode(data.summary.bounty[0].value))
            );
        }

        bounty_block.append(bounty_ul);

        var cont = $("#detailcontent");
        cont.empty();
        cont.append($("<h1>").text("Detail"));
        cont.append(
            $("<div>").addClass("row")
                .append($("<div>").addClass("col-md-7")
                            .append($("<h2>").text(data.name))
                    )
                .append($("<div>").addClass("col-md-5 smallertext")
                            .append($("<div>").addClass("text-right")
                                        .append($("<label>").text("Closing Date"))
                                        .append(document.createTextNode(data.summary.closing_date))
                                )
                            .append($("<div>").addClass("text-right")
                                        .append($("<label>").text("Invitation Closing Date"))
                                        .append(document.createTextNode(data.summary.invitation_closing_date))
                                )
                    )
            )
            .append(
                $("<div>").addClass("description_block")
                    .append($("<div>").addClass("single_row")
                                .append($("<label>").text("Brand"))
                                .append(document.createTextNode(brandtext))
                        )
                    .append($("<div>").addClass("single_row")
                                .append($("<label>").text("Location"))
                                .append(document.createTextNode(locationtext))
                        )
                    .append($("<div>").addClass("single_row")
                                .append($("<label>").text("Tag"))
                                .append(document.createTextNode(tagstext))
                        )
            )
            .append($("<hr>"))
            .append(data.detail.description_brief == "" ? null :
                $("<div>").addClass("description_block")
                    .append($("<h2>").text("Description Brief"))
                    .append($("<pre>").html(data.detail.description_brief))
            )
            .append(data.detail.deliverable_brief == "" ? null :
                $("<div>").addClass("description_block")
                    .append($("<h2>").text("Deliverables Brief"))
                    .append($("<pre>").html(data.detail.deliverable_brief))
            )
            .append(data.detail.other_brief == "" ? null :
                $("<div>").addClass("description_block")
                    .append($("<h2>").text("Additional Brief"))
                    .append($("<pre>").html(data.detail.other_brief))
            )
            .append($("<hr>"))
            .append(deliverable_block)
            .append(sample_block)
            .append($("<hr>"))
            .append(bounty_block);

        return cont;
    }
};