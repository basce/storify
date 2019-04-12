var storify = storify || {};
storify.creator = storify.creator || {};

storify.creator.projectList_invite = {
    addElementIfNotExist:function(){
        if(!$("#rejectModal").length){
            $("body").append(
                $("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"rejectModal"})
                    .append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
                        .append($("<div>").addClass("modal-content")
                            .append($("<div>").addClass("modal-header")
                                .append($("<h5>").addClass("modal-title").text("You have declined an assignment!"))
                                .append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal","aria-label":"Close"})
                                    .append($("<span>").text("×").attr({"aria-hidden":"true"}))
                                )
                            )
                            .append($("<form>").addClass("custommsg").attr({novalidate:true})
                                .append($("<div>").addClass("modal-body")
                                    .append($("<div>").addClass("row")
                                        .append($("<div>").addClass("form-group col-md-10 offset-md-1")
                                            .append($("<h3>").text("It's such a pity! We will miss your work!"))
                                            .append($("<label>").attr({for:"reject_reason"}).addClass("col-form-label").text("Will you tell us why?")
                                            )
                                            .append($("<input>").attr({name:"reject_reason", type:"text", "placeholder":"Let us know how we can make it good for you.", "value":""}))
                                            .append($("<input>").attr({name:"invitation_id", type:"hidden", "value":""}))
                                        )
                                    )
                                )
                                .append($("<div>").addClass("modal-footer")
                                    .append($("<button>").addClass("cancelbtn btn btn-primary").attr({"data-dismiss":"modal","aria-label":"Close"}).text("Cancel"))
                                    .append($("<button>").addClass("btn btn-primary").text("Reject").click(function(e){
                                        e.preventDefault();
                                        storify.creator.projectList_invite.rejectInvitation();
                                    }))
                                )
                            )
                        )
                    )
            );
        }
        if(!$("#acceptModal").length){
            $("body").append(
                $("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"acceptModal"})
                    .append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({role:"document"})
                        .append($("<div>").addClass("modal-content")
                            .append($("<div>").addClass("modal-header")
                                .append($("<h5>").addClass("modal-title").text("You have accepted an assignment!"))
                                .append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal","aria-label":"Close"})
                                    .append($("<span>").text("×").attr({"aria-hidden":"true"}))
                                )
                            )
                            .append($("<form>").addClass("custommsg").attr({novalidate:true})
                                .append($("<div>").addClass("modal-body")
                                    .append($("<h3>").text("To great success! Please submit by ")
                                                    .append($("<span>").addClass("deadline"))
                                                    .append(document.createTextNode("."))
                                        )
                                    .append($("<p>").text("To view project details and submit your work, please go to the 'Ongoing' folder and click on the project you have accepted.")
                                        )
                                )
                                .append($("<div>").addClass("modal-footer")
                                    .append($("<a>").addClass("btn btn-primary acceptlink").text("Let's Go!"))
                                )
                            )
                        )
                    )
            );
        }
    },
    createProjectItem:function(data, project_id, invitation_id){
        var div = $("<div>").addClass("project-item").attr({id:project_id}),
            ribbon = $("<div>").addClass("ribbon-featured")
                        .append($("<div>").addClass("ribbon-start"))
                        .append($("<div>").addClass("ribbon-content").text("Due Soon"))
                        .append($("<div>").addClass("ribbon-end")
                            .append($("<figure>").addClass("ribbon-shadow"))
                            ),
            brand_tags = $("<div>").addClass("tags"),
            title = $("<h3>"),
            summary = data.summary,
            div_image = $("<div>").addClass("image"),
            div_location = null,
            div_description = $("<p>");

        $.each(summary.brand, function(index,value){
            brand_tags.append($("<span>").text(value.name).attr({"data-term_id":value.term_id}))
        });

        title.text(data.name);
        title.prepend($("<div>").addClass("meta")
            .append(
                $("<figure>").append($("<i>").addClass("fa fa-calendar-o"))
                            .append(document.createTextNode(" Created "+summary.formatted_created_date2))
            )
            .append(
                $("<figure>").append($("<i>").addClass("fa fa-calendar-o"))
                            .append(document.createTextNode(" Accept "+summary.formatted_invitation_closing_date2))
            )
            .append(
                $("<figure>").append($("<i>").addClass("fa fa-calendar-o"))
                            .append(document.createTextNode(" Deliver "+summary.formatted_closing_date2))
            )
        );
        $.each(summary.tag, function(index,value){
            title.append($("<span>").addClass("tag").text(value.name).attr({"data-term_id":value.term_id}))
        });

        var bountytext = "",
            bountyhtml = "";
        if(summary.bounty.length == 2){
            bountytext = "$"+storify.project.formatMoney(summary.bounty[0].value) + " & " + summary.bounty[1].value;
            bountyhtml = '<i class="fa fa-money" aria-hidden="true"></i> & <i class="fa fa-gift" aria-hidden="true"></i>';
        }else{
            if(summary.bounty[0].type == "cash"){
                bountytext = "$"+storify.project.formatMoney(summary.bounty[0].value);
                bountyhtml = '<i class="fa fa-money" aria-hidden="true"></i>';
            }else{
                bountytext = summary.bounty[0].value;
                bountyhtml = '<i class="fa fa-gift" aria-hidden="true"></i>';
            }
        }

        if(data.before_time_left && data.before_time_left < 129600){
            div.append(ribbon);
        }

        if(summary.location.length){
            div_location = $("<h4>").addClass("location");
            $.each(summary.location, function(index,value){
                if(index != 0){
                    div_location.append(document.createTextNode(", "));
                }
                div_location.append($("<span>").attr({"data-term_id":value.term_id}).text(value.name));
            });
        }

        if(summary.display_image){
            div_image.css({"background-image":"url("+summary.display_image+")"});
        }

        $.each(summary.deliverables_ar, function(index,value){
            if(index != 0){
                div_description.append(document.createTextNode(" | "+value.amount+" "));
            }else{
                div_description.append(document.createTextNode(value.amount+" "));
            }
            if(value.type == "photo"){
                div_description.append($("<i>").addClass("fa fa-camera").attr({"aria-hidden":"true"}));
            }else{
                div_description.append($("<i>").addClass("fa fa-video-camera").attr({"aria-hidden":"true"}));
            }
        });

        div_description.append($("<br>"));
        div_description.append(document.createTextNode(summary.description));

        div.append($("<div>").addClass("wrapper")
            .append(div_image
                .append(brand_tags)
                .append($("<div>").attr({title:bountytext}).addClass("price").html(bountyhtml))
            )
            .append($("<div>").addClass("content")
                .append(title)
                .append(div_location)
                .append($("<div>").addClass("description")
                    .append(div_description)
                )
                .append($("<div>").addClass("actions")
                    .append($("<a>").attr({href:"#"}).text("Details")
                        .click(function(e){
                            e.preventDefault();
                            storify.creator.detail_invite.viewDetail(project_id);
                        })
                    )
                    .append($("<a>").attr({href:"#"}).text("Accept")
                        .click(function(e){
                            e.preventDefault();
                            $("#acceptModal .deadline").text(summary.formatted_closing_date2);
                            storify.creator.projectList_invite.acceptInvitation(invitation_id);
                        })
                    )
                    .append($("<a>").attr({href:"#"}).text("Reject")
                        .click(function(e){
                            e.preventDefault();

                            $("#rejectModal input[name='invitation_id']").val(invitation_id);
                            $("#rejectModal").attr({"data-project_id":project_id})
                            $("#rejectModal").modal("show");
                            //storify.creator.projectList_invite.rejectInvitation(invitation_id);
                        })
                    )
                )
            )
        )

        return div;
    },
    createPrototypeItem:function(data_pair, project_id){
        var div = $("<div>").addClass("item prototype_item").css({"background-color":"#FFF","padding":"20px"});

        $.each(data_pair, function(index,value){
            div.append(
                $("<div>").addClass("clearfix")
                    .append($("<label>").text(index))
                    .append($("<span>").html(value))
                )
        });

        //accept and deny button
        div.append(
            $("<div>").addClass("")
                .append(
                    $("<a>").addClass("btn btn-primary text-caps btn-rounded btn-framed").attr({href:"#"})
                        .css({"margin-right":"5px"})
                        .text("Details")
                        .click(function(e){
                            e.preventDefault();
                            storify.creator.detail_invite.viewDetail(project_id);
                        })
                    )
        );

        return div;
    },
    _gettingProject:false,
    getProject:function(onComplete){
        storify.creator.projectList_invite.addElementIfNotExist();
    	if(storify.creator.projectList_invite._gettingProject) return;
    	storify.creator.projectList_invite._gettingProject = true;

    	var $grid = $("#invite_grid"),
            cur_page = parseInt($grid.attr("data-page"), 10),
            sort = $grid.attr("data-sort"),
            filter = $grid.attr("data-filter");

        cur_page = cur_page ? cur_page + 1 : 1;

        $("#invitationloadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
        $.ajax({
            method: "POST",
            dataType: "json",
            data: {
                method:"getInvitation",
                filter:filter,
                page:cur_page,
                sort:sort
            },
            success:function(rs){
                storify.creator.projectList_invite._gettingProject = false;
                if(rs.error){
                    console.log(rs);
                    alert(rs.msg);
                }else{
                    $grid.attr({'data-page':rs.result.page});
                    $("#invitationloadmore").text("Load More").blur();
                    if(parseInt(rs.result.page, 10) < parseInt(rs.result.totalpage, 10)){
                        $("#invitationloadmore").css({display:"inline-block"});
                    }else{
                        $("#invitationloadmore").css({display:"none"});
                    }

                    $.each(rs.result.data, function(index,value){
                        var $temp = storify.creator.projectList_invite.createProjectItem(value, value.id, value.invitation_id);
                        $grid.append($temp);
                        ScrollReveal().reveal($temp, storify.slideUp);
                    });
                    if(onComplete)onComplete();
                }
            }
        });
    },
    _rejectingInvitation:false,
    rejectInvitation:function(onComplete){
        if(storify.creator.projectList_invite._rejectingInvitation) return;
        storify.creator.projectList_invite._rejectingInvitation = true;

        var invitation_id = $("#rejectModal input[name='invitation_id']").val(),
        reject_reason = $("#rejectModal input[name='reject_reason']").val(),
        project_id = $("#rejectModal").attr("data-project_id");

        $.ajax({
            method: "POST",
            dataType: "json",
            data: {
                method: "reject",
                invitation_id: invitation_id,
                reason: reject_reason
            },
            success:function(rs){
                if(rs.error){
                    console.log(rs);
                    alert(rs.msg);
                }else{
                    $(".project-item[id='"+project_id+"']").remove();
                    $("#rejectModal").modal("hide");
                }
            }
        });
    },
    _acceptingInvitation:false,
    acceptInvitation:function(invitation_id){
        if(storify.creator.projectList_invite._acceptingInvitation) return;
        storify.creator.projectList_invite._acceptingInvitation = true;

        $.ajax({
            method: "POST",
            dataType: "json",
            data:{
                method: "accept",
                invitation_id: invitation_id
            },
            success:function(rs){
                storify.creator.projectList_invite._acceptingInvitation = false;
                if(rs.error){
                    console.log(rs);
                    alert(rs.msg);
                }else{
                    $(".project-item[id='"+rs.added+"']").remove();
                    $("#acceptModal").modal("show");
                    $("#acceptModal .acceptlink").attr({href:"/user@"+rs.user_id+"/projects/ongoing/"+rs.added, target:"_self"});
                }
            }
        });
    }
}