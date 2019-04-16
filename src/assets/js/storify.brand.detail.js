var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.detail = {
    addElementIfNotExist:function(){
        if( !$("#detailModal").length  && 0){
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
                                            .append($("<section>").addClass("section-container invite-section")
                                                .append($("<h1>").text("Creator Management"))
                                                .append($("<div>").addClass("form-group")
                                                    .append($("<div>").addClass("input-group mb-3 invite-input")
                                                        .append($("<select>").addClass("form-control customselect").attr({name:"invite[]", id:"invite", "data-placeholder":"Select Creator", multiple:true}))
                                                        .append($("<div>").addClass("input-group-append")
                                                            .append($("<button>").addClass("btn btn-outline-secondary sendInviteButton").attr({type:"button"}).text("Invite"))
                                                        )
                                                    )
                                                )
                                                .append($("<div>").addClass("invite-group"))
                                                .append($("<div>").addClass("invite-summary"))
                                            )
                                            .append($("<section>").addClass("section-container deliverable-section")
                                                .append($("<h1>").text("Deliverable Management"))
                                                .append($("<div>").addClass("deliverable-groups"))
                                            )
                                        )
                                    )
                                )
                            )
                            .append($("<div>").addClass("modal-footer").append(
                                $("<button>").addClass("btn btn-primary small viewCompletion").text("View completion")
                                )
                                .append($("<button>").addClass("btn btn-primary small").text("Edit").click(function(){
                                    $("#editDialog").modal("show");
                                }))
                            )
                        )
                    )
            );

            //add selectize 
            $("#invite").selectize({
                plugins:['restore_on_backspace', 'no_results'],
                delimiter:',',
                valueField: 'userid',
                labelField: 'name',
                searchField: ['name', 'igusername'],
                persist: false,
                loadThrottle: 600,
                create: false,
                allowEmptyOption: true,
                render: {
                    option: function( item, escape ){
                        return '<div class="selectize_iger">' +
                            '<div class="img" style="background-image:url('+escape(item.image_url)+')" ></div>'+
                            '<div class="title">' +
                                '<span class="name">' + escape(item.name) + '</span>' +
                                '<span class="igusername">' + escape(item.igusername) + '</span>' +
                            '</div>' +
                        '</div>';
                    }
                },
                load: function( query, callback ){
                    if(query.length < 3) return callback();
                    console.log(query);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data:{
                            name: query,
                            method: "getCreator"
                        },
                        error: function(){
                            callback();
                        },
                        success: function(res){
                            callback(res.data);
                        }
                    });
                },
                onItemAdd: function( value, item ){
                    var selected = $("#invite")[0].selectize.getValue();
                    if(selected.length){
                        console.log(selected);
                        $.each(selected, function(index,value){
                            //add invited creator
                            var tempitem = $("#invite")[0].selectize.options[value];
                            storify.brand.invitation.sendSingleInvitation(tempitem.userid);
                        });
                    }
                    $("#invite")[0].selectize.clear(true);
                }
            });

            //$(".sendInviteButton").click(storify.brand.invitation.invite_click);
            $(".viewCompletion").click(function(e){
                e.preventDefault();
                var project_id = $(this).attr("data-project_id");
                storify.brand.completion.getCompletion(project_id, function(){});
            });
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
                                        .append($("<a>").addClass("nav-link").attr({id:"creator-tab","data-toggle":"tab",href:"#creator",role:"tab","aria-controls":"creator","aria-expanded":true}).text("Creators"))
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
                                    .append($("<div>").addClass("tab-pane fade").attr({id:"creator",role:"tabpanel","aria-labelledby":"creator-tab"})
                                        .append($("<div>").addClass("creatorcontent").attr({id:"creator-content"})
                                            .append($("<h5>").text("People who have accepted this project: ")
                                                .append($("<span>").addClass("no_of_invited").text("0"))
                                            )
                                            .append($("<div>").addClass("form-group")
                                                .append($("<div>").addClass("input-group creator-input")
                                                    .append($("<select>").addClass("form-control customselect").attr({name:"invite[]", id:"invite", "data-placeholder":"Select creators for this project.", multiple:true}))
                                                    /*.append($("<div>").addClass("input-group-append")
                                                        .append($("<button>").addClass("btn btn-outline-secondary sendInviteButton").attr({type:"button"}).text("Add Creator"))
                                                    ) */
                                                )
                                            )
                                            .append($("<div>").addClass("invite-group"))
                                        )
                                    )
                                    .append($("<div>").addClass("tab-pane fade").attr({id:"submission",role:"tabpanel","aria-labelledby":"submission-tab"})
                                        .append($("<div>").addClass("deliverable-groups").attr({id:"submission-content"}))
                                    )
                                    .append($("<div>").addClass("tab-pane fade").attr({id:"final",role:"tabpanel","aria-labelledby":"final-tab"})
                                        .append($("<div>").addClass("finalcontent").attr({id:"final-content"}))
                                    )
                                )
                            )
                            .append($("<div>").addClass("modal-footer"))
                        )
                    )
            );

            //add selectize 
            $("#invite").selectize({
                plugins:['restore_on_backspace', 'no_results'],
                delimiter:',',
                valueField: 'userid',
                labelField: 'name',
                searchField: ['name', 'igusername'],
                persist: false,
                loadThrottle: 600,
                create: false,
                allowEmptyOption: true,
                render: {
                    option: function( item, escape ){
                        return '<div class="selectize_iger">' +
                            '<div class="img" style="background-image:url('+escape(item.image_url)+')" ></div>'+
                            '<div class="title">' +
                                '<span class="name">' + escape(item.name) + '</span>' +
                                '<span class="igusername">' + escape(item.igusername) + '</span>' +
                            '</div>' +
                        '</div>';
                    }
                },
                load: function( query, callback ){
                    if(query.length < 3) return callback();
                    console.log(query);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data:{
                            name: query,
                            method: "getCreator"
                        },
                        error: function(){
                            callback();
                        },
                        success: function(res){
                            callback(res.data);
                        }
                    });
                },
                onItemAdd: function( value, item ){
                    var selected = $("#invite")[0].selectize.getValue();
                    if(selected.length){
                        console.log(selected);
                        $.each(selected, function(index,value){
                            //add invited creator
                            var tempitem = $("#invite")[0].selectize.options[value];
                            storify.brand.invitation.sendSingleInvitation(tempitem.userid);
                        });
                    }
                    $("#invite")[0].selectize.clear(true);
                }
            });
        }
    },
    _gettingDetail:false,
    viewDetail:function(project_id){
        if(storify.brand.detail._gettingDetail) return;
        storify.brand.detail._gettingDetail = true;
        storify.brand.detail.addElementIfNotExist();
        storify.loading.show();
        $("#newDetailModal .nav-tabs").each(function(index,value){
          $(value).find("a:eq(0)").tab("show");
        });
        $(".invite-group").empty();
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "getDetail",
                project_id: project_id
            },
            success:function(rs){
                storify.brand.detail._gettingDetail = false;
                if(rs.error){
                    storify.loading.hide();
                }else{
                    _project_id = project_id;
                    storify.project._project_id = project_id;
                    storify.brand.invitation.getList();
                    storify.project.user.getAllUser(function(){
                        storify.brand.deliverable.getList(function(){
                            storify.brand.completion.getCompletion(project_id, function(){
                                storify.loading.hide();
                                storify.brand.detail.createDetail(rs.data);
                                //$("#detailModal").modal();
                                $("#newDetailModal").modal("show");
                                //setup edit dialog
                                updateEditProject(rs.data);
                            });
                        });
                    });
                }
            }
        });
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
        div.append($("<h5>").text("You are going to reward good people well."))
            .append(cashtable)
            .append(gifttable)
            .append($("<h5>").text("Each creator will receive S$"+storify.project.formatMoney(totalcash)+", and entitlements, if any."));
        $("#bounty-content").empty()
            .append(div);
    },
    createDetail:function(data){
         //bramd
        var brandtext = [];
        if(data.summary.brand && data.summary.brand.length){
            $.each(data.summary.brand, function(index, value){
                brandtext.push(value.name);
            });
        }

        $("#submission-content .brand").text(brandtext);

        //locations
        var locationtext = [];
        if(data.summary.location && data.summary.location.length){
            $.each(data.summary.location, function(index, value){
                locationtext.push(value.name);
            });
        }

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
        storify.brand.detail.createBountyTable(data.detail);

        var cont = $("#brief-content");
        cont.empty();

        cont.append(
                $("<div>").addClass("project_header")
                    .append($("<div>").addClass("brand").text(brandtext.join(", ")))
                    .append($("<h2>").text(data.name+" ")
                            .append($("<a>").attr({href:"#"}).addClass("detail text-caps underline").text("Edit")
                                    .click(function(e){
                                        e.preventDefault();
                                        $("#editDialog .modal-body").scrollTop(0);
                                        $("#editDialog").modal("show");
                                    })
                                )
                        )
                    .append($("<div>").addClass("location").text(locationtext.join(", ")))
                    .append($("<div>").addClass("date_cont")
                        .append($("<div>").addClass("text-right")
                                .append($("<i>").addClass("fa fa-calendar-o"))
                                .append(document.createTextNode(" Accept "+data.summary.formatted_closing_date))
                            )
                        .append($("<div>").addClass("text-right")
                                .append($("<i>").addClass("fa fa-calendar-o"))
                                .append(document.createTextNode(" Deliver "+data.summary.formatted_invitation_closing_date))
                            )
                    )
            )
            .append(deliverable_block)
            .append($("<pre>").html(data.detail.description_brief))
            .append($("<pre>").html(data.detail.deliverable_brief))
            ;
        cont.append(owlImages);

        if(owlImages){
            var _interval = setInterval(function(args) {
                // body
                if(owlImages.parent().is(":visible")){
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
        }
        return cont;

        /*
        $("#bounty-content").empty();
        storify.creator.detail.createBountyTable(data.detail);

        var bounty_block = null, bounty_ul;
        bounty_block = $("<div>").addClass("description_block");
        bounty_block.append($("<h2>").text("Bounty"));

        bounty_ul = $("<ul>");
        if(data.detail.bounty_type == "both"){
            bounty_ul.append(
                $("<li>").append($("<label>").text("Cash"))
                        .append(document.createTextNode("$"+data.summary.bounty[0].value+" ( $"+data.detail.cost_per_photo+" for each "))
                        .append($("<i>").addClass("fa fa-camera"))
                        .append(document.createTextNode(", $"+data.detail.cost_per_video+" for each "))
                        .append($("<i>").addClass("fa fa-video-camera"))
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
                        .append($("<i>").addClass("fa fa-camera"))
                        .append(document.createTextNode(", $"+data.detail.cost_per_video+" for each "))
                        .append($("<i>").addClass("fa fa-video-camera"))
                        .append(document.createTextNode(" )"))
            );
        }else{
            bounty_ul.append(
                $("<li>").append($("<label>").text("Gift"))
                        .append(document.createTextNode(data.summary.bounty[0].value))
            );
        }

        bounty_block.append(bounty_ul);

        $("#detailModal .viewCompletion").attr({"data-project_id":data.detail.project_id})

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
        */
    }
};
