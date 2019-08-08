var storify = storify || {};
storify.creator = storify.creator || {};

storify.creator.detail = {
    addElementIfNotExist: function() {
        var div;
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
                                            .append($("<section>").addClass("section-container deliverable-section")
                                                .append($("<h1>").text("Deliverable Management"))
                                                .append($("<div>").addClass("deliverable-groups"))
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
        */
        if (!$("#newDetailModal").length) {
            $("body").append(
                $("<modal>").addClass("modal").attr({ tabindex: -1, role: "dialog", id: "newDetailModal" })
                .append($("<div>").addClass("modal-dialog modal-dialog-centered modal-custom-xl").attr({ role: "document" })
                    .append($("<div>").addClass("modal-content")
                        .append($("<div>").addClass("modal-header")
                            .append($("<h5>").addClass("modal-title").text(""))
                            .append($("<button>").addClass("close").attr({ type: "button", "data-dismiss": "modal", "aria-label": "Close" }).append($("<span>").attr({ "aria-hidden": "true" }).html("&times")))
                        )
                        .append($("<div>").addClass("modal-body")
                            .append($("<ul>").addClass("nav nav-tabs").attr({ role: "tablist", id: "tab_control" })
                                .append($("<li>").addClass("nav-item")
                                    .append($("<a>").addClass("nav-link active").attr({ id: "brief-tab", "data-toggle": "tab", href: "#brief", role: "tab", "aria-controls": "brief", "aria-expanded": true }).text("Brief"))
                                )
                                .append($("<li>").addClass("nav-item")
                                    .append($("<a>").addClass("nav-link").attr({ id: "bounty-tab", "data-toggle": "tab", href: "#bounty", role: "tab", "aria-controls": "bounty", "aria-expanded": true }).text("Bounty"))
                                )
                                .append($("<li>").addClass("nav-item")
                                    .append($("<a>").addClass("nav-link").attr({ id: "submission-tab", "data-toggle": "tab", href: "#submission", role: "tab", "aria-controls": "submission", "aria-expanded": true }).text("Submission"))
                                )
                            )
                            .append($("<div>").addClass("tab-content").attr({ id: "tab_content" })
                                .append($("<div>").addClass("tab-pane fade show active").attr({ id: "brief", role: "tabpanel", "aria-labelledby": "brief-tab" })
                                    .append($("<div>").addClass("detailcontent").attr({ id: "brief-content" }))
                                )
                                .append($("<div>").addClass("tab-pane fade").attr({ id: "bounty", role: "tabpanel", "aria-labelledby": "bounty-tab" })
                                    .append($("<div>").addClass("bountycontent").attr({ id: "bounty-content" }))
                                )
                                .append($("<div>").addClass("tab-pane fade").attr({ id: "submission", role: "submissionpanel", "aria-labelledby": "submission-tab" })
                                    .append($("<div>").addClass("submissioncontent").attr({ id: "submission-content" })
                                        .append($("<div>").addClass("submissionsection")
                                            .append($("<div>").addClass("brand"))
                                            .append($("<h2>").addClass("submission_title"))
                                            /*
                                            .append($("<div>").addClass("icon_select")
                                                .append($("<a>").attr({href:"#"}).append($("<i>").addClass("fa fa-camera")))
                                                .append($("<a>").attr({href:"#"}).append($("<i>").addClass("fa fa-video-camera")))
                                            )*/
                                            .append($("<ul>").addClass("nav nav-tabs").attr({ role: "tablist", id: "tab_control3" })
                                                .append($("<li>").addClass("nav-item")
                                                    .append($("<a>").addClass("nav-link active").attr({ id: "submission-file-tab", "data-toggle": "tab", href: "#submission-file", role: "tab", "aria-controls": "submission-file", "aria-expanded": true })
                                                        .append($("<span>").text("Upload"))
                                                        .append(document.createTextNode(" "))
                                                        .append($("<i>").addClass("fa fa-file-image-o"))
                                                    )
                                                )
                                                .append($("<li>").addClass("nav-item")
                                                    .append($("<a>").addClass("nav-link").attr({ id: "submission-text-tab", "data-toggle": "tab", href: "#submission-text", role: "tab", "aria-controls": "submission_text", "aria-expanded": true })
                                                        .append($("<span>").text("Link"))
                                                        .append(document.createTextNode(" "))
                                                        .append($("<i>").addClass("fa fa-link"))
                                                    )
                                                )
                                            )
                                            .append($("<div>").addClass("tab-content").attr({ id: "tab_content3" })
                                                .append($("<div>").addClass("tab-pane fade show active").attr({ id: "submission-file", role: "tabpanel", "aria-labelledby": "submission-file-tab" })
                                                    .append($("<div>").addClass("submission_form submission_file")
                                                        .append($("<div>").addClass("submission_form_top")
                                                            .append($("<div>").addClass("form-width")
                                                                .append($("<input>").addClass("form-control submission-file hide").attr({ id:"creator-submit-file", type: "file" })
                                                                                .change(function(e){
                                                                                    if(e.target.files && e.target.files.length){
                                                                                        $("label[for='creator-submit-file']").find(".file-comment").text(e.target.files[0].name);
                                                                                    }
                                                                                })
                                                                )
                                                                .append($("<label>").attr({for:"creator-submit-file"}).addClass("submission-label")
                                                                    .append($("<span>").addClass("button").text("Choose File"))
                                                                    .append($("<span>").addClass("file-comment").text("No file selected."))
                                                                )
                                                            )
                                                            .append($("<div>").addClass("form-width")
                                                                .append($("<textarea>").addClass("form-control submission_description").attr({ rows: 3, placeholder: "Enter your caption here." }))
                                                            )
                                                            .append($("<a>").attr({ href: "#" }).addClass("form_submit_btn").text("Add").click(function(e) {
                                                                e.preventDefault();
                                                                if ($(this).hasClass("disabled")) {
                                                                    return;
                                                                }
                                                                storify.creator.detail.submitSubmisionFile();
                                                            }))
                                                        )
                                                        .append($("<div>").addClass("submission_form_bottom")
                                                            .append($("<div>").addClass("form-width")
                                                                .append($("<div>").addClass("alert alert-danger hide").text("Please enter URL"))
                                                            )
                                                            .append($("<div>").addClass("form-width progressbar hide")
                                                                .append($("<div>").addClass("progressbar-inner"))
                                                            )
                                                        )
                                                    )
                                                )
                                                .append($("<div>").addClass("tab-pane fade").attr({ id: "submission-text", role: "tabpanel", "aria-labelledby": "submission-text-tab" })
                                                    .append($("<div>").addClass("submission_form submission_text")
                                                        .append($("<div>").addClass("submission_form_top")
                                                            .append($("<div>").addClass("form-width")
                                                                .append($("<textarea>").addClass("form-control submission_url").attr({ rows: 2, placeholder: "Place link to your asset here." }))
                                                            )
                                                            .append($("<div>").addClass("form-width")
                                                                .append($("<textarea>").addClass("form-control submission_description").attr({ rows: 3, placeholder: "Enter your caption here." }))
                                                            )
                                                            .append($("<a>").attr({ href: "#" }).addClass("form_submit_btn").text("Add").click(function(e) {
                                                                e.preventDefault();
                                                                if ($(this).hasClass("disabled")) {
                                                                    return;
                                                                }
                                                                storify.creator.detail.submitSubmision();
                                                            }))
                                                        )
                                                        .append($("<div>").addClass("submission_form_bottom")
                                                            .append($("<div>").addClass("form-width")
                                                                .append($("<div>").addClass("alert alert-danger hide").text("Please enter URL"))
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                        .append($("<hr>"))
                                        .append($("<div>").addClass("submissionlist")
                                            /*
                                            .append($("<div>").addClass("listtop")
                                                .append($("<div>").addClass("listtopleft")
                                                    .append($("<span>").addClass("togglebutton-box"))
                                                )
                                                .append($("<span>").addClass("listnotification").text("no photo to deliver"))
                                            )*/
                                            .append($("<ul>").addClass("nav nav-tabs").attr({ role: "tablist", id: "tab_control2" })
                                                .append($("<li>").addClass("nav-item")
                                                    .append($("<a>").addClass("nav-link active").attr({ id: "photo-tab", "data-toggle": "tab", href: "#photolist", role: "tab", "aria-controls": "photolist", "aria-expanded": true })
                                                        .append($("<span>").text("99/100"))
                                                        .append(document.createTextNode(" "))
                                                        .append($("<i>").addClass("fa fa-camera"))
                                                    )
                                                )
                                                .append($("<li>").addClass("nav-item")
                                                    .append($("<a>").addClass("nav-link").attr({ id: "video-tab", "data-toggle": "tab", href: "#videolist", role: "tab", "aria-controls": "videolist", "aria-expanded": true })
                                                        .append($("<span>").text("100/100"))
                                                        .append(document.createTextNode(" "))
                                                        .append($("<i>").addClass("fa fa-video-camera"))
                                                    )
                                                )
                                            )
                                            .append($("<div>").addClass("tab-content").attr({ id: "tab_content2" })
                                                .append($("<div>").addClass("tab-pane fade show active").attr({ id: "photolist", role: "tabpanel", "aria-labelledby": "photolist-tab" })
                                                    .append($("<div>").addClass("list photolist"))
                                                )
                                                .append($("<div>").addClass("tab-pane fade").attr({ id: "videolist", role: "tabpanel", "aria-labelledby": "videolist-tab" })
                                                    .append($("<div>").addClass("list videolist"))
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                        .append($("<div>").addClass("modal-footer"))
                    )
                )
            );

            /*
            $("#newDetailModal .icon_select a").click(function(e){
                e.preventDefault();
                if($(this).hasClass("on")){
                    $(this).removeClass("on");
                    $(this).blur();
                    $(".form_submit_btn").removeClass("disabled");
                }else{
                    $("#newDetailModal .icon_select a").removeClass("on");
                    $(this).addClass("on");
                }
            });
            */
            $('#tab_control2 a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                //change
                var target = $(e.target).attr("href");

                if (target == "#photolist") {
                    storify.creator.detail.viewlist("photo");
                } else {
                    storify.creator.detail.viewlist("video");
                }
            });
        }
        if (!$("#rejectModal").length) {
            /*
            $("body").append(
                $("<modal>").addClass("modal").attr({ tabindex: -1, role: "dialog", id: "rejectModal" })
                .append($("<div>").addClass("modal-dialog modal-dialog-centered").attr({ role: "document" })
                    .append($("<div>").addClass("modal-content")
                        .append($("<div>").addClass("modal-header")
                            .append($("<h5>").addClass("modal-title").text(""))
                            .append($("<button>").addClass("close").attr({ type: "button", "data-dismiss": "modal", "aria-label": "Close" }).append($("<span>").attr({ "aria-hidden": "true" }).html("&times")))
                        )
                        .append($("<div>").addClass("modal-body")

                        )
                        .append($("<div>").addClass("modal-footer")
                            .append(
                                $("<button>").addClass("btn btn-primary small").text("Ok").attr({ type: "button", "data-dismiss": "modal", "aria-label": "Close" })
                            )
                        )
                    )
                )
            );
            */

            div = $(storify.template.simpleModal(
                {
                    titlehtml:``,
                    bodyhtml:``
                },
                "rejectModal",
                [   
                    {
                        label:"ok",
                        attr:{type:"button", "data-dismiss":"modal", "aria-label":"Close", class:"btn btn-primary small"}
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
            );*/

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
    createBountyTable: function(detail) {
        var cashtable = $("<div>").addClass("bountycash2"),
            gifttable = null;

        var totalcash = 0;

        if (detail.bounty_type == "gift") {
            detail.cost_per_photo = 0;
            detail.cost_per_video = 0;
        }

        totalcash = detail.no_of_photo * detail.cost_per_photo + detail.no_of_video * detail.cost_per_video;

        cashtable.append($("<div>").addClass("bountyrow")
            .append($("<h2>")
                .append($("<span>").text(detail.no_of_photo)
                    .append($("<i>").addClass("fa fa-camera"))
                )
                .append($("<span>").text("S$" + storify.project.formatMoney(detail.cost_per_photo) + " each"))
            ));
        cashtable.append($("<div>").addClass("bountyrow")
            .append($("<h2>")
                .append($("<span>").text(detail.no_of_video)
                    .append($("<i>").addClass("fa fa-video-camera"))
                )
                .append($("<span>").text("S$" + storify.project.formatMoney(detail.cost_per_video) + " each"))
            ));

        if (detail.bounty_type == "both" || detail.bounty_type == "gift") {
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
            .append($("<h5>").text("In total, you will receive S$" + storify.project.formatMoney(totalcash) + ", and entitlements, if any."));
        $("#bounty-content").empty()
            .append(div);
    },
    _gettingDetail: false,
    viewDetail: function(project_id, onComplete) {
        if (storify.creator.detail._gettingDetail) return;
        storify.creator.detail._gettingDetail = true;
        storify.creator.detail.addElementIfNotExist();
        storify.loading.show();
        $("#newDetailModal .nav-tabs").each(function(index, value) {
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
            success: function(rs) {
                storify.creator.detail._gettingDetail = false;
                if (rs.error) {
                    storify.loading.hide();
                } else {
                    storify.project._project_id = project_id;
                    storify.project.user.getAllUser(function() {
                        storify.creator.deliverable.getList(function() {
                            storify.loading.hide();
                            storify.creator.detail.createDetail(rs.data);
                            storify.creator.detail.resetSubmission();
                            storify.creator.detail.getSubmission();
                            $("#newDetailModal .modal-body").scrollTop(0);
                            if(onComplete)onComplete();
                            $("#newDetailModal").modal();
                        });
                    });
                }
            }
        });
    },
    //createDetail
    createDetail: function(data) {
        //bramd
        var brandtext = [];
        if (data.summary.brand && data.summary.brand.length) {
            $.each(data.summary.brand, function(index, value) {
                brandtext.push(value.name);
            });
        }

        $("#submission-content .brand").text(brandtext);

        //locations
        var locationtext = [];
        if (data.summary.location && data.summary.location.length) {
            $.each(data.summary.location, function(index, value) {
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
        if (+data.detail.no_of_photo && +data.detail.no_of_video) {
            deliverable_block
                .append(document.createTextNode(data.detail.no_of_photo + " "))
                .append($("<i>").addClass("fa fa-camera"))
                .append(document.createTextNode(" | " + data.detail.no_of_video + " "))
                .append($("<i>").addClass("fa fa-video-camera"));
        } else if (+data.detail.no_of_photo) {
            deliverable_block
                .append(document.createTextNode(data.detail.no_of_photo + " "))
                .append($("<i>").addClass("fa fa-camera"));
        } else if (+data.detail.no_of_video) {
            deliverable_block
                .append(document.createTextNode(data.detail.no_of_video + " "))
                .append($("<i>").addClass("fa fa-video-camera"));
        }

        //sample block
        var owlImages = null;

        if (data.sample.length) {
            owlImages = $("<div>").addClass("samples_cont owl-carousel owl-theme");

            $.each(data.sample, function(index, value) {
                owlImages.append(
                    $("<a>").addClass("sample_clickable")
                    .attr({ href: value.URL, target: "_blank" })
                    .css({ "background-image": "url(" + value.URL + ")" })
                );
            });
        }

        $("#bounty-content").empty();
        storify.creator.detail.createBountyTable(data.detail);

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
                        .append(document.createTextNode(" Accept " + data.summary.formatted_invitation_closing_date))
                    )
                    .append($("<div>").addClass("text-right")
                        .append($("<i>").addClass("fa fa-calendar-o"))
                        .append(document.createTextNode(" Deliver " + data.summary.formatted_closing_date))
                    )
                )
            )
            .append(deliverable_block)
            .append($("<div>").addClass("linkify").html(data.detail.description_brief))
            .append($("<div>").addClass("linkify").html(data.detail.deliverable_brief));
        cont.append(owlImages);

        if (owlImages) {
            var _interval = setInterval(function(args) {
                // body
                if (owlImages && owlImages.parent().is(":visible")) {
                    console.log("owlimage done");
                    owlImages.owlCarousel({
                        loop: false,
                        autoplay: true,
                        margin: 10,
                        responsiveClass: true,
                        responsiveBaseElement: "#brief-content",
                        responsive: {
                            0: {
                                items: 1,
                                nav: true
                            },
                            250: {
                                items: 2,
                                nav: true
                            },
                            650: {
                                items: 3,
                                nav: true
                            },
                            1000: {
                                items: 5,
                                nav: true
                            }
                        }
                    });
                    clearInterval(_interval);
                }
            }, 50);
        }
        $(".linkify").linkify({
            target: "_blank"
        });
        return cont;
    },
    _submittingfile:false,
    //submit file
    submitSubmisionFile: function() {
        var selectType = "",
            error_alert = $("#submission-file .submission_form_bottom .alert");

        error_alert.addClass("hide");

        if ($("#photolist").is(":visible")) {
            selectType = "photo";
        } else {
            selectType = "video";
        }

        if (!$("#submission-file .submission-file").val()) {
            error_alert.text("Please select a file to upload.").removeClass("hide");
            return;
        }

        //get file data
        var file = $("#submission-file .submission-file")[0].files[0];

        if ($("#submission-file .submission_description").val() && $("#submission-file .submission_description").val().length >= 800) {
            error_alert.text("You have exceeded the character limit. Please shorten your caption.").removeClass("hide");
            return;
        }

        var caption = $("#submission-file .submission_description").val();

        if (storify.creator.detail._submittingfile) return;
        storify.creator.detail._submittingfile = true;
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "makeSubmissionFile",
                project_id: storify.project._project_id,
                type: selectType,
                file_name:file.name,
                file_size:file.size,
                file_mime:file.type,
                remark: $("#submission-content .submission_file .submission_description").val()
            },
            success: function(rs) {
                storify.creator.detail._submittingfile = false;
                if (rs.error) {
                    storify.loading.hide();
                    if (rs.msg == "cap reached") {
                        error_alert.text("You cannot submit any more.");
                    }
                } else {
                    if(rs.success){
                        //upload file
                        storify.creator.detail._S3Upload(file, rs.url, function(){
                            storify.creator.detail._updateFileStatus(rs.id, caption, selectType, function(){
                                storify.creator.detail.resetSubmission();
                                storify.creator.detail.getSubmission(selectType);
                            }, function(str){
                                error_alert.text(str);
                            });
                        });
                    }else{
                        error_alert.text(rs.msg);   
                    }
                }
            }
        });
    },
    _updateFileStatus:function(id, caption, type, onComplete, onError){
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "confirmUpload",
                id:id,
                caption: caption,
                type: type
            },
            success: function(rs) {
                if (rs.error) {
                    if(onError){
                        onError(rs.msg);
                    }
                } else {
                    if(onComplete){
                        onComplete();
                    }
                }
            }
        });
    },
    _progress:function(perc){
        if(perc == 0){
            $(".progressbar").addClass("hide");
        }else{
            $(".progressbar").removeClass("hide");
            $(".progressbar-inner").css({width:(perc*100) + "%"})
        }
    },
    _S3Uploading:false,
    _S3Upload:function(file, url, onComplete){
        if (storify.creator.detail._S3Uploading) { 
            return; 
        }
        storify.creator.detail._S3Uploading = true;

        $.ajax({
           xhr: function() {
               var xhr = new window.XMLHttpRequest();
               xhr.upload.addEventListener("progress", function(evt){
                    storify.creator.detail._progress(evt.loaded / evt.total);
               }, false);
               xhr.addEventListener("progress", function(evt){
                    storify.creator.detail._progress(evt.loaded / evt.total);
               }, false);
               return xhr;
            },
            beforeSend: function(request) {
                request.setRequestHeader('Content-Disposition', 'attachment');
            },
            url:url,
            type:"PUT",
            data:file,
            processData:false,
            contentType:false,
            success:function(evt){
                storify.creator.detail._progress(0);
                storify.creator.detail._S3Uploading = false;
                if(onComplete){
                    onComplete();
                }
            },
            error:function(evt){
                storify.creator.detail._progress(0);
                storify.creator.detail.resetSubmission();
                alert("Upload Error");
            }
        });
    },
    //submit submission
    _submitting:false,
    submitSubmision: function() {
        var selectType = "",
            error_alert = $("#submission-text .submission_form_bottom .alert");

        error_alert.addClass("hide");

        if ($("#photolist").is(":visible")) {
            selectType = "photo";
        } else {
            selectType = "video";
        }

        if (!$("#submission-text .submission_url").val()) {
            error_alert.text("Please enter submission file link").removeClass("hide");
            return;
        }

        if ($("#submission-text .submission_description").val() && $("#submission-text .submission_description").val().length >= 800) {
            error_alert.text("You have exceeded the character limit. Please shorten your caption.").removeClass("hide");
            return;
        }

        if (storify.creator.detail._submitting) return;
        storify.creator.detail._submitting = true;
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "makeSubmission",
                project_id: storify.project._project_id,
                type: selectType,
                URL: $("#submission-text .submission_url").val(),
                remark: $("#submission-text .submission_description").val()
            },
            success: function(rs) {
                storify.creator.detail._submitting = false;
                if (rs.error) {
                    storify.loading.hide();
                    if (rs.msg == "cap reached") {
                        error_alert.text("You cannot submit any more.");
                    }
                } else {
                    storify.creator.detail.resetSubmission();
                    //refresh list
                    storify.creator.detail.getSubmission(selectType);
                }
            }
        });
    },
    //resetSubmission
    resetSubmission: function() {
        $("#submission-content .submission-file").val("");
        $("#submission-content .file-comment").text("No file selected.");
        $("#submission-content .submission_url").val("");
        $("#submission-content .submission_description").val("");
        $("#submission-content .progressbar").addClass("hide");
    },
    createSubmissionBlock: function(data) {
        var iconClass,
            mainClass,
            div,
            actiondiv = $("<div>").addClass("urlaction");

        if (data.type == "photo") {
            iconClass = "fa-camera";
            mainClass = "photo";
        } else {
            iconClass = "fa-video-camera";
            mainClass = "video";
        }

        switch (data.status) {
            case "accepted":
                actiondiv.append($("<span>").addClass("accept")
                    .append($("<i>").addClass("fa fa-thumbs-up").attr({ "aria-hidden": true }))
                );
                break;
            case "rejected":
                //pending
                actiondiv.append($("<a>").addClass("bin").attr({ href: "#" })
                        .append($("<i>").addClass("fa fa-trash-o").attr({ "aria-hidden": true }))
                        .click(function(e) {
                            e.preventDefault();
                            storify.creator.detail.removeSubmission(data.id, data.type);
                        })
                    )
                    .append($("<a>").addClass("reject").attr({ href: "#" })
                        .append($("<i>").addClass("fa fa-thumbs-down").attr({ "aria-hidden": true }))
                        .click(function(e) {
                            e.preventDefault();
                            $("#rejectModal .modal-body").empty()
                                .append($("<p>").text(data.admin_remark ? data.admin_remark : "no reason given"));
                            $("#rejectModal").modal("show");
                        })
                    );
                break;
            default:
                //pending
                actiondiv.append($("<a>").addClass("bin").attr({ href: "#" })
                    .append($("<i>").addClass("fa fa-trash-o").attr({ "aria-hidden": true }))
                    .click(function(e) {
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
                            .append($("<div>").addClass("file-download-link").text(storify.creator.detail.shortenFileName(data.filename)+" ("+data.mime+")")
                                            .append($("<i>").addClass("fa fa-arrow-circle-down").css({"margin-left":".5rem"}))
                                            .click(function(e){
                                                e.preventDefault();
                                                storify.creator.detail._showDownloadDialog(data.file_id);
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
            div = $("<div>").addClass("submission " + mainClass).attr({ o: data.id })
                .append($("<i>").addClass("submission-icon fa " + iconClass))
                .append($("<div>").addClass("urlrow")
                    .append($("<div>").addClass("urlinput")
                        .append($("<input>").addClass("form-control").attr({ readonly: true }).val(data.URL))
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
                                                .attr({href:rs.filelink, target:"_blank", download:rs.filename})
                                                .text(storify.creator.detail.shortenFileName(rs.filename)+" ("+rs.filemime+")")
                                                .append($("<i>").addClass("fa fa-arrow-circle-down").css({"margin-left":".5rem"}));
                    $("#downloadLinkModal").find(".filesize").text("");
                    $("#downloadLinkModal").find(".filemime").text("");
                    $("#downloadLinkModal").find(".download").attr({href:rs.filelink, download:rs.filename})
                    $("#downloadLinkModal").modal("show");
                }
            }

        })
    },
    _removingSubmission: false,
    removeSubmission(id, type) {
        if (storify.creator.detail._removingSubmission) return;
        storify.creator.detail._removingSubmission = true;
        storify.loading.show();
        $.ajax({
            method: "POST",
            dataType: "json",
            data: {
                method: "removeSubmission",
                id: id
            },
            success: function(rs) {
                storify.creator.detail._removingSubmission = false;
                storify.loading.hide();
                if (rs.error) {
                    alert(rs.msg);
                } else {
                    storify.creator.detail.getSubmission(type);
                }
            }
        });
    },
    updatelistnotification: function(type) {
        /*
        var a = $("#submission-content .togglebutton-box"),
            b = $("#submission-content .listnotification"),
            count, max;

        if(type == "photo"){
            count = a.attr("n_photo");
            max = a.attr("m_photo");
            if(count < max){
                if((max - count) == 1){
                    b.text("1 more photo to deliver");
                }else{
                    b.text( (max-count) + " more photos to deliver");
                }
            }else{
                b.text("No more photos to deliver");
            }
        }else{
            count = a.attr("n_video");
            max = a.attr("m_video");
            if(count < max){
                if((max - count) == 1){
                    b.text("1 more video to deliver");
                }else{
                    b.text( (max-count) + " more videos to deliver");
                }
            }else{
                b.text("No more videos to deliver");
            }
        }
        */
    },
    viewlist: function(type) {
        var max_photo = $("#tab_control2").attr("m_photo") ? +$("#tab_control2").attr("m_photo") : 0,
            max_video = $("#tab_control2").attr("m_video") ? +$("#tab_control2").attr("m_video") : 0,
            n_photo = $("#tab_control2").attr("n_photo") ? +$("#tab_control2").attr("n_photo") : 0,
            n_video = $("#tab_control2").attr("n_video") ? +$("#tab_control2").attr("n_video") : 0;

        console.log({
            type: type,
            max_photo: max_photo,
            max_video: max_video,
            n_photo: n_photo,
            n_video: n_video
        });
        if (type == "photo") {
            if (n_photo < max_photo) {
                //still available
                $(".form_submit_btn").removeClass("disabled");
            } else {
                $(".form_submit_btn").addClass("disabled");
            }
        } else {
            if (n_video < max_video) {
                $(".form_submit_btn").removeClass("disabled");
            } else {
                $(".form_submit_btn").addClass("disabled");
            }
        }

        //update text
        $("#photo-tab span").text(n_photo + "/" + max_photo);
        $("#video-tab span").text(n_video + "/" + max_video);
    },
    listSubmissions: function(no_p, no_v, data, viewtype) {
        $("#submission-content .photolist").empty();
        $("#submission-content .videolist").empty();

        var number_photo = 0,
            number_video = 0,
            total_assets = no_p + no_v,
            number_submitted = 0,
            number_finalised = 0,
            asset_label = "";
        $.each(data, function(index, value) {
            if (value.type == "photo") {
                number_photo++;
                $("#submission-content .photolist").append(storify.creator.detail.createSubmissionBlock(value));
            } else if (value.type == "video") {
                number_video++;
                $("#submission-content .videolist").append(storify.creator.detail.createSubmissionBlock(value));
            }
            if (value.status == "accepted") {
                number_finalised++;
            }
            number_submitted++;
        });

        if (number_photo == 0) {
            $("#submission-content .photolist").append($("<p>").text("No submissions have been made, yet."));
        }

        if (number_video == 0) {
            $("#submission-content .videolist").append($("<p>").text("No submissions have been made, yet."));
        }

        $("#tab_control2").attr({
            "m_photo": no_p,
            "m_video": no_v,
            "n_photo": number_photo,
            "n_video": number_video
        });

        //submission_title
        //1 / 3 assets submitted and 0 finalised
        if (number_submitted == 1) {
            asset_label = "asset";
        } else {
            asset_label = "assets";
        }

        $("#submission-content .submission_title").text(number_submitted + " / " + total_assets + " " + asset_label + " and " + number_finalised + " finalised");

        if (viewtype == "photo") {
            $('#tab_control2 a[href="#photolist"]').tab("show");
        } else {
            $('#tab_control2 a[href="#videolist"]').tab("show");
        }
        storify.creator.detail.viewlist(viewtype);
    },
    gettingSubmissions: false,
    getSubmission: function(viewtype) {
        if (storify.creator.detail.gettingSubmissions) return;
        storify.creator.detail.gettingSubmissions = true;
        storify.loading.show();
        $.ajax({
            method: "POST",
            dataType: 'json',
            data: {
                method: "getSubmissions",
                project_id: storify.project._project_id
            },
            success: function(rs) {
                storify.creator.detail.gettingSubmissions = false;
                storify.loading.hide();
                if (rs.error) {

                } else {
                    if (!viewtype) {
                        //if viewtype is not set
                        if (rs.data.length) {
                            var number_photo = 0,
                                number_video = 0;
                            $.each(rs.data, function(index, value) {
                                if (value.type == "photo") {
                                    number_photo++;
                                } else {
                                    number_video++;
                                }
                            });

                            if (!parseInt(rs.no_of_photo, 10)) {
                                viewtype = "video";
                            } else if (parseInt(rs.no_of_photo, 10) < number_photo) {
                                viewtype = "photo";
                            } else if (!parseInt(rs.no_of_video, 10)) {
                                viewtype = "photo";
                            } else if (parseInt(rs.no_of_video, 10) < number_video) {
                                viewtype = "video";
                            } else {
                                viewtype = "photo";
                            }
                        } else {
                            viewtype = "photo";
                        }
                    }
                    storify.creator.detail.listSubmissions(parseInt(rs.no_of_photo, 10), parseInt(rs.no_of_video, 10), rs.data, viewtype);
                }
            }
        });
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