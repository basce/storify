var storify = storify || {};
storify.brand = storify.brand || {};

storify.brand.detail = {
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

				}
			});

			$(".sendInviteButton").click(storify.brand.invitation.invite_click);
            $(".viewCompletion").click(function(e){
                e.preventDefault();
                var project_id = $(this).attr("data-project_id");
                storify.brand.completion.getCompletion(project_id, function(){});
            });
		}
	},
	_gettingDetail:false,
	viewDetail:function(project_id){
		if(storify.brand.detail._gettingDetail) return;
		storify.brand.detail._gettingDetail = true;
		storify.brand.detail.addElementIfNotExist();
		storify.loading.show();
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
                            storify.loading.hide();
                            storify.brand.detail.createDetail(rs.data);
                            $("#detailModal").modal();

                            //setup edit dialog
                            updateEditProject(rs.data);
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
	}
};
