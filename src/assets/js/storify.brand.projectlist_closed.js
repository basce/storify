var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.projectList_closed = {
    createProjectItem:function(data, project_id){
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

        title.text(data.name)
            .prepend($("<div>").addClass("meta")
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

        var bountytext = "";
        if(summary.bounty.length == 2){
            bountytext = "$" + summary.bounty[0].value + " & " + summary.bounty[1].value;
        }else{
            if(summary.bounty[0].type == "cash"){
                bountytext = "$" + summary.bounty[0].value;
            }else{
                bountytext = summary.bounty[0].value;
            }
        }

        if(data.duesoon){
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
                .append($("<div>").attr({title:bountytext}).addClass("price").text(bountytext))
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
                            storify.brand.detail_closed.viewDetail(project_id);
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
                            storify.brand.detail_closed.viewDetail(project_id);
                        })
                    )
        );

        return div;
    },
    _gettingProject:false,
    getProject:function(onComplete){
        if(storify.brand.projectList_closed._gettingProject) return;
        storify.brand.projectList_closed._gettingProject = true;

        var $grid = $("#closed_grid"),
            cur_page = parseInt($grid.attr("data-page"), 10),
            sort = $grid.attr("data-sort"),
            filter = $grid.attr("data-filter");

        cur_page = cur_page ? cur_page + 1 : 1;

        $("#closeloadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
        $.ajax({
            method: "POST",
            dataType: "json",
            data: {
                method:"getProject",
                filter:filter,
                page:cur_page,
                sort:sort
            },
            success:function(rs){
                storify.brand.projectList_closed._gettingProject = false;
                if(rs.error){
                    alert(rs.msg);
                }else{
                    $grid.attr({'data-page':rs.result.page});
                    $("#closeloadmore").text("Load More").blur();
                    if(parseInt(rs.result.page, 10) < parseInt(rs.result.totalpage, 10)){
                        $("#closeloadmore").css({display:"inline-block"});
                    }else{
                        $("#closeloadmore").css({display:"none"});
                    }

                    $.each(rs.result.data, function(index,value){
                        var $temp = storify.brand.projectList_closed.createProjectItem(value, value.id);
                        $grid.append($temp);
                        ScrollReveal().reveal($temp, storify.slideUp);
                    });
                    if(onComplete)onComplete();
                }
            }
        });
    }
}