var storify = storify || {};
storify.creator = storify.creator || {};

storify.creator.detail_invite = {
    addElementIfNotExist:function(){
        if( !$("#detailModal").length ){
            $("body").append(
                $("<modal>").addClass("modal").attr({tabindex:-1, role:"dialog", id:"detailModal"})
                    .append($("<div>").addClass("modal-dialog modal-dialog-centered modal-xl").attr({role:"document"})
                        .append($("<div>").addClass("modal-content")
                            .append($("<div>").addClass("modal-header")
                                .append($("<h5>").addClass("modal-title").text(""))
                                .append($("<button>").addClass("close").attr({type:"button","data-dismiss":"modal", "aria-label":"Close"}).append($("<span>").attr({"aria-hidden":"true"}).html("&times")))
                            )
                            .append($("<div>").addClass("modal-body")
                                .append($("<div>").addClass("row")
                                    .append($("<div>").addClass("col-md-10 offset-md-1 detailcontent")
                                        .append($("<section>").addClass("section-container detailcontent").attr({id:"detailcontent"}))
                                    )
                                )
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
                                )
                                .append($("<div>").addClass("tab-content").attr({id:"tab_content"})
                                    .append($("<div>").addClass("tab-pane fade show active").attr({id:"brief",role:"tabpanel","aria-labelledby":"brief-tab"})
                                        .append($("<div>").addClass("detailcontent").attr({id:"brief-content"}))
                                    )
                                    .append($("<div>").addClass("tab-pane fade").attr({id:"bounty",role:"tabpanel","aria-labelledby":"bounty-tab"})
                                        .append($("<div>").addClass("bountycontent").attr({id:"bounty-content"}))
                                    )
                                )
                            )
                            .append($("<div>").addClass("modal-footer"))
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
    _gettingDetail:false,
    viewDetail:function(project_id){
        if(storify.creator.detail_invite._gettingDetail) return;
        storify.creator.detail_invite._gettingDetail = true;
        storify.creator.detail_invite.addElementIfNotExist();
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
                storify.creator.detail_invite._gettingDetail = false;
                if(rs.error){
                    storify.loading.hide();
                }else{
                    storify.project._project_id = project_id;
                    storify.creator.detail_invite.createDetail(rs.data);
                    storify.loading.hide();
                    $("#newDetailModal").modal("show");
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
        storify.creator.detail_invite.createBountyTable(data.detail);

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
            if(owlImages && owlImages.parent() && owlImages.parent().is(":visible")){
                console.log("owlimage done");
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
    }
};