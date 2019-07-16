var storify = storify || {};
storify.creator = storify.creator || {};

storify.creator.projectList = {
    createListItem:(data, project_id) => `
        <div class="project-item" id="${project_id}">
            ${
                (before_time_left=>{
                    if(before_time_left && before_time_left < 129600){
                        return `
    <div class="ribbon-featured">
        <div class="ribbon-start"></div>
        <div class="ribbon-content">Due Soon</div>
        <div class="ribbon-end">
            <figure class="ribbon-shadow"></figure>
        </div>
    </div>
                        `;
                    }
                })(data.before_time_left)
            }
            <div class="wrapper">
                <div class="image" ${
                    (display_image=>{
                        if(display_image){
                            return `style="background-image:url(${display_image});"`;
                        }
                    })(data.summary.display_image)
                }>
                    <div class="tags">${
                        (brands=>{
                            if(brands.length){
                                return brands.map(brand=>{
                                    if(brand.term_id){
                                        return `<span data-term_id="${brand.term_id}">${brand.name}</span`;
                                    }else{
                                        return ``;
                                    }
                                }).join("");
                            }else{
                                return ``;
                            }
                        })(data.summary.brand)
                    }</div>
                    <div title="${
                        (bounty=>{
                            if(bounty.length == 2){
                                return `$${storify.project.formatMoney(bounty[0].value)} & ${bounty[1].value}`;
                            }else{
                                if(bounty[0].type == "cash"){
                                    return `$${storify.project.formatMoney(bounty[0].value)}`;
                                }else{
                                    return `$${bounty[0].value}`;
                                }
                            }
                        })(data.summary.bounty)
                    }" class="price">${
                        (bounty=>{
                            if(bounty.length == 2){
                                return `<i class="fa fa-money" aria-hidden="true"></i> & <i class="fa fa-gift" aria-hidden="true"></i>`;
                            }else{
                                if(bounty[0].type == "cash"){
                                    return `<i class="fa fa-money" aria-hidden="true"></i>`;
                                }else{
                                    return `<i class="fa fa-gift" aria-hidden="true"></i>`;
                                }
                            }
                        })(data.summary.bounty)
                    }</div>
                </div>
                <div class="content">
                    <h3>
                        <div class="meta">
                            <figure>
                                <i class="fa fa-calendar-o"></i> Created ${data.summary.formatted_created_date2}
                            </figure>
                            <figure>
                                <i class="fa fa-calendar-o"></i> Accept ${data.summary.formatted_invitation_closing_date2}
                            </figure>
                            <figure>
                                <i class="fa fa-calendar-o"></i> Delvier ${data.summary.formatted_closing_date2}
                            </figure>
                        </div>
                        ${data.name}
                        ${
                            (tags=>{
                                if(tags.length){
                                    return tags.map(tag=>{
                                        return `<span class="tag" data-term_id="${tag.term_id}">${tag.name}</span>`;
                                    }).join("");
                                }else{
                                    return ``;
                                }
                            })(data.summary.tag)
                        }
                    </h3>
                    ${
                        (locations=>{
                            if(locations.length){
                                var templocations = locations.map(location=>{
                                    return `<span data-term_id="${location.term_id}">${location.name}</span>`;
                                }).join(", ");
                                return `<h4 class="location">${templocations}</h4>`;
                            }else{
                                return ``;
                            }
                        })(data.summary.location)
                    }
                    <div class="description one-button">
                        <p class="linkify">
                            ${
                                (deliverables=>{
                                    return deliverables.map( (deliverable, index)=>{
                                        return `${deliverable.amount} ${deliverable.type == "photo"?`<i class="fa fa-camera" aria-hidden="true"></i>`:`<i class="fa fa-video-camera" aria-hidden="true"></i>`}`;
                                    }).join(" | ");
                                })(data.summary.deliverables_ar)
                            }
                            <br>
                            ${data.summary.description}
                        </p>
                    </div>
                    <div class="actions">
                        <a href="#">Details</a>
                    </div>
                </div>
            </div>
        </div>
    `,
    createProjectItem:function(data, project_id){
        /*
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
            div_description = $("<p>").addClass("linkify");

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
                .append($("<div>").addClass("description one-button")
                    .append(div_description)
                )
                .append($("<div>").addClass("actions")
                    .append($("<a>").attr({href:"#"}).text("Details")
                        .click(function(e){
                            e.preventDefault();
                            storify.creator.detail.viewDetail(project_id);
                        })
                    )
                )
            )
        )
        */
        
        //var div = $(storify.creator.projectList.createListItem(data, project_id));
        var div = $(storify.template.createListItem(data, project_id, [{classname:"detail", label:"Detail"}]));
        div.find(".actions .detail").click(function(e){
            e.preventDefault();
            storify.creator.detail.viewDetail(project_id);
        });
        return div;
    },
    _gettingProject:false,
    getProject:function(onComplete){
    	if(storify.creator.projectList._gettingProject) return;
    	storify.creator.projectList._gettingProject = true;

    	var $grid = $("#ongoing_grid"),
            cur_page = parseInt($grid.attr("data-page"), 10),
            sort = $grid.attr("data-sort"),
            filter = $grid.attr("data-filter");

        cur_page = cur_page ? cur_page + 1 : 1;

        $("#ongoingloadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
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
                storify.creator.projectList._gettingProject = false;
                if(rs.error){
                    alert(rs.msg);
                }else{
                    $grid.attr({'data-page':rs.result.page});
                    $("#ongoingloadmore").text("Load More").blur();
                    if(parseInt(rs.result.page, 10) < parseInt(rs.result.totalpage, 10)){
                        $("#ongoingloadmore").css({display:"inline-block"});
                    }else{
                        $("#ongoingloadmore").css({display:"none"});
                    }

                    $.each(rs.result.data, function(index,value){

                        var $temp = storify.creator.projectList.createProjectItem(value, value.id);
                        $grid.append($temp);
                        ScrollReveal().reveal($temp, storify.slideUp);
                    });
                    $(".linkify").linkify({
                        target: "_blank"
                    });

                    if(!+rs.result.total){
                        $grid.append($("<p>").text("No ongoing projects now. Accept your next invite and kickstart a project!"));
                    }
                    
                    if(onComplete)onComplete();
                }
            }
        });
    }
}