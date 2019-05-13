////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// jQuery
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function($) {
    "use strict";

    var slideUp = {
        scale: 0.5,
        opacity: null
    };

    ScrollReveal().reveal('.scrollreveal', slideUp);

    $(".scrolldowntogrid").click(function(e){
        e.preventDefault();
         $('html, body').animate({
            scrollTop: $("#tellstory").offset().top
        }, 500);
    });

    $("#order").change(function(e){
        if($(this).val()){
            var extra_query = "";
            if(window._query !== undefined){
                if(window._query.category !== undefined){
                    $.each(window._query.category, function(index,value){
                        extra_query += "&category%5B%5D="+value;
                    });
                }
                if(window._query.country !== undefined){
                    $.each(window._query.country, function(index,value){
                        extra_query += "&country%5B%5D="+value;
                    });
                }
                if(window._query.language !== undefined){
                    $.each(window._query.language, function(index,value){
                        extra_query += "&language%5B%5D="+value;
                    });
                }
            }
            window.location.href = "?order="+$(this).val()+extra_query;
        }
    });

    function bookmarkTrigger(itemid, type, obj){
        var bookmark;
        if(obj.hasClass("active")){
            bookmark = 0;
        }else{
            bookmark = 1;
        }
        $.ajax({
            url: "/json",
            method: "POST",
            data: {
                method:"triggerbookmark",
                item_id:itemid,
                type:type,
                bookmark:bookmark
            },
            success:function(rs){
                if(rs.error){
                    console.log(rs.msg); //error
                    if(rs.msg == "require login"){
                        if($("#memberonlymodal").length){
                            $("#memberonlymodal").modal();
                        }else{
                            alert("bookmark function only for logged in user.");
                        }
                    }
                }else{
                    if(parseInt(rs.bookmark)){
                        obj.addClass("active");
                    }else{
                        obj.removeClass("active");
                    }
                }
            },
            dataType: "json"

        });
    }

    $(".bookmarkpeople").click(function(e){
        e.preventDefault();
        bookmarkTrigger($(this).attr("o"),$(this).attr("c"),$(this));
    });

    function format1(n) {
      return n.toFixed(0).replace(/./g, function(c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
      });
    }

    function createPostGridItem($obj){
        var h3 = $("<h3>");

        if($obj.post_tag && $obj.post_tag.length){
            var tag_group = $("<span>").addClass("tag_group");
            $.each($obj.post_tag, function(index,value){
                tag_group.append($("<a>").addClass("tag category").attr({href:"/listing?category%5B%5D="+value.term_id}).text(value.name));
            });
            h3.append(tag_group);
        }

         h3.append($("<span>").addClass("tag")
                .append($("<i>").addClass("fa fa-clock-o"))
                .append(document.createTextNode($obj.modified)));

        var h4 = $("<h4>").addClass("location");

        if($obj.post_country && $obj.post_country.length){
            $.each($obj.post_country, function(index, value){
                h4.append($("<a>").attr({href:"/listing?country%5B%5D="+value.term_id}).text(value.name));
            });
        }


        var item = $("<div>").addClass("item")
            .append($("<div>").addClass("wrapper")
                .append($("<div>").addClass("image")
                        .append(h3)
                        .append($("<a>").addClass("image-wrapper background-image").attr({href:$obj.link, target:"_blank"})
                            .css({'background-image':'url('+$obj.image_hires+')'})
                            .append($("<img>").attr({src:$obj.image_hires}))
                    )
                )
                .append(h4)
                .append($("<div>").addClass("meta")
                    .append($("<figure>")
                        .append($("<i>").addClass("fa fa-heart"))
                        .append(document.createTextNode(format1(parseInt($obj.likes, 10))))
                    )
                    .append($("<figure>")
                        .append($("<i>").addClass("fa fa-comments"))
                        .append(document.createTextNode(format1(parseInt($obj.comments, 10))))
                    )
                    .append($("<a>")
                        .addClass("fa bookmark " + ($obj.bookmark ? "active":""))
                        .attr({href:"#", o:$obj.id, c:'story'})
                        .click(function(e){
                            e.preventDefault();
                            bookmarkTrigger($(this).attr("o"), $(this).attr("c"), $(this));
                        })
                    )
                )
                .append($("<div>").addClass("description")
                    .append($("<p>").text($obj.caption))
                )
                .append(
                    $("<a>").addClass("detail text-caps underline").text("Read").attr({href:$obj.link, target:"_blank"})
                )
            );
        return item;
    }

    function createIndexPostGridItem($obj){

        var h3 = $("<h3>");

        if($obj.post_tag && $obj.post_tag.length){
            var tag_group = $("<span>").addClass("tag_group");
            $.each($obj.post_tag, function(index,value){
                tag_group.append($("<a>").addClass("tag category").attr({href:"/listing?category%5B%5D="+value.term_id}).text(value.name));
            });
            h3.append(tag_group);
        }

        //h3.append($("<a>").addClass("title").attr({href:"/"+$obj.igusername+"/"}).text($obj.igusername));

        /*
        if($obj.instagrammer_language && $obj.instagrammer_language.length){
            $.each($obj.instagrammer_language, function(index, value){
                h3.append($("<span>").addClass("tag").text(value.name));
            });
        }
        */
        h3.append($("<span>").addClass("tag")
                                    .append($("<i>").addClass("fa fa-clock-o"))
                                    .append(document.createTextNode($obj.modified)));

        var h4 = $("<h4>").addClass("location");

        if($obj.instagrammer_country && $obj.instagrammer_country.length){
            $.each($obj.instagrammer_country, function(index, value){
                h4.append($("<a>").attr({href:"/listing?country%5B%5D="+value.term_id}).text(value.name));
            });
        }

        var aver_likes;
        if(parseInt($obj.average_likes,10) > 0){
            aver_likes = $("<div>").addClass("price")
                .append($("<span>").addClass("appendix").text('Average'))
                .append($("<i>").addClass("fa fa-heart"))
                .append(document.createTextNode(format1(parseInt($obj.average_likes,10))));
        }

        var $external_url = "";
        if($obj.external_url){
            $external_url = $("<a>").attr({href:$obj.external_url, target:"_blank"}).text($obj.external_url);
        }

        var item = $("<div>").addClass("item scrollreveal")
                    .append($("<div>").addClass("wrapper")
                        .append($("<div>").addClass("image")
                                .append(h3)
                                .append($("<a>").addClass("image-wrapper background-image").attr({href:"/"+$obj.igusername+"/"})
                                                .css({'background-image':'url('+$obj.ig_profile_pic+')'})
                                                .append($("<img>").attr({src:$obj.ig_profile_pic}))
                                    )
                            )
                        .append(h4)
                        .append(aver_likes)
                        .append($("<div>").addClass("meta")
                                .append($("<figure>").text(format1(parseInt($obj.follows_by_count)))
                                        .prepend($("<i>").addClass("fa fa-users"))
                                    )
                                .append($("<figure>").text(format1(parseInt($obj.media_count)))
                                        .prepend($("<i>").addClass("fa fa-image"))
                                    )
                                .append($("<a>")
                                    .addClass("fa bookmark " + ($obj.bookmark ? "active":""))
                                    .attr({href:"#", o:$obj.id, c:'story'})
                                    .click(function(e){
                                        e.preventDefault();
                                        bookmarkTrigger($(this).attr("o"), $(this).attr("c"), $(this));
                                    })
                                )
                            )
                        .append($("<div>").addClass("description")
                                .append($("<p>")
                                    .html($obj.biography)
                                    )
                                /*
                                .append($("<span>")
                                    .append($("<i>").addClass("fa fa-clock-o"))
                                    .append(document.createTextNode($obj.modified))
                                    )*/
                                /*.append($external_url)*/
                            )
                        .append(
                            $("<a>").addClass("detail text-caps underline").text("Details").attr({href:"/"+$obj.igusername+"/"})
                            )
                    );

        return item;
    }

    function createIndexGridItem($obj){

        var h3 = $("<h3>");

        if($obj.instagrammer_tag && $obj.instagrammer_tag.length){
            var tag_group = $("<span>").addClass("tag_group");
            $.each($obj.instagrammer_tag, function(index,value){
                tag_group.append($("<a>").addClass("tag category").attr({href:"/listing?category%5B%5D="+value.term_id}).text(value.name));
            });
            h3.append(tag_group);
        }

        if(+$obj.verified){
            h3.append(
                $("<a>").addClass("title").attr({href:"/"+$obj.igusername+"/"}).text($obj.igusername)
                        .append($("<span>").attr({title:"verified"}).addClass("verified").text("✔"))
            );
        }else{
            h3.append($("<a>").addClass("title").attr({href:"/"+$obj.igusername+"/"}).text($obj.igusername));
        }

        /*
        if($obj.instagrammer_language && $obj.instagrammer_language.length){
            $.each($obj.instagrammer_language, function(index, value){
                h3.append($("<span>").addClass("tag").text(value.name));
            });
        }
        */
        h3.append($("<span>").addClass("tag")
                        .append($("<i>").addClass("fa fa-clock-o"))
                        .append(document.createTextNode($obj.modified)));

        var h4 = $("<h4>").addClass("location");

        if($obj.instagrammer_country && $obj.instagrammer_country.length){
            $.each($obj.instagrammer_country, function(index, value){
                h4.append($("<a>").attr({href:"/listing?country%5B%5D="+value.term_id}).text(value.name));
            });
        }

        var aver_likes;
        if(parseInt($obj.average_likes,10) > 0){
            aver_likes = $("<div>").addClass("price")
                .append($("<span>").addClass("appendix").text('Average'))
                .append($("<i>").addClass("fa fa-heart"))
                .append(document.createTextNode(format1(parseInt($obj.average_likes,10))));
        }

        var $external_url = "";
        if($obj.external_url){
            $external_url = $("<a>").attr({href:$obj.external_url, target:"_blank"}).text($obj.external_url);
        }

        var item = $("<div>").addClass("item scrollreveal")
                    .append($("<div>").addClass("wrapper")
                        .append($("<div>").addClass("image")
                                .append(h3)
                                .append($("<a>").addClass("image-wrapper background-image").attr({href:"/"+$obj.igusername+"/"})
                                                .css({'background-image':'url('+$obj.ig_profile_pic+')'})
                                                .append($("<img>").attr({src:$obj.ig_profile_pic}))
                                    )
                            )
                        .append(h4)
                        .append(aver_likes)
                        .append($("<div>").addClass("meta")
                                .append($("<figure>").text(format1(parseInt($obj.follows_by_count)))
                                        .prepend($("<i>").addClass("fa fa-users"))
                                    )
                                .append($("<figure>").text(format1(parseInt($obj.media_count)))
                                        .prepend($("<i>").addClass("fa fa-image"))
                                    )
                                .append($("<a>")
                                    .addClass("fa bookmark " + ($obj.bookmark ? "active":""))
                                    .attr({href:"#", o:$obj.id, c:'people'})
                                    .click(function(e){
                                        e.preventDefault();
                                        bookmarkTrigger($(this).attr("o"), $(this).attr("c"), $(this));
                                    })
                                )
                            )
                        .append($("<div>").addClass("description")
                                .append($("<p>")
                                    .html($obj.biography)
                                    )
                                /*
                                .append($("<span>")
                                    .append($("<i>").addClass("fa fa-clock-o"))
                                    .append(document.createTextNode($obj.modified))
                                    )*/
                                /*.append($external_url)*/
                            )
                        .append(
                            $("<a>").addClass("detail text-caps underline").text("Details").attr({href:"/"+$obj.igusername+"/"})
                            )
                    );

        return item;
    }

    var _updatingPostItems = false;
    function updatePostItems(){
        var $grid = $(".items.grid"),
            cur_page = parseInt($grid.attr("data-page"), 10),
            sort = $grid.attr("data-sort");

        if(_updatingPostItems) return;
        _updatingPostItems = true;
        cur_page = cur_page ? cur_page + 1 : 1;

        $("#loadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
        $.ajax({
            url: "/json",
            method: "POST",
            data: {
                method:"getPosts",
                iger:$grid.attr("data-nc_iger"),
                page:cur_page,
                sort:sort
            },
            success:function(rs){
                _updatingPostItems = false;
                if(rs.error){
                    console.log(rs.msg); //error
                }else{
                    $grid.attr({'data-page':rs.result.page});
                    $("#loadmore").text("Load More").blur();
                    if(parseInt(rs.result.page,10) < parseInt(rs.result.totalpage,10)){
                        //not last page
                        $("#loadmore").css({display:"inline-block"});
                    }else{
                        //last page
                        $("#loadmore").css({display:"none"});
                    }

                    //add to masonry
                    $.each(rs.result.data, function(index,value){
                        var $temp = createPostGridItem(value);
                        $grid.append( $temp );
                         ScrollReveal().reveal($temp, slideUp);
                    });

                    $(".total_iger").text(rs.result.total);
                    $(".story_tell_cont").removeAttr("style");
                }
            },
            dataType: "json"

        });
    }

    var _updatingItems = false;
    function updateItems(){
        var $grid = $(".items.grid"),
            cur_page = parseInt($grid.attr("data-page"), 10),
            sort = $grid.attr("data-sort");

        if(_updatingItems) return;
        _updatingItems = true;
        cur_page = cur_page ? cur_page + 1 : 1;

        $("#loadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
        $.ajax({
            url: "/json",
            method: "POST",
            data: {
                method:"getlisting",
                category:$grid.attr("data-category"),
                country:$grid.attr("data-country"),
                language:$grid.attr("data-language"),
                page:cur_page,
                sort:sort
            },
            success:function(rs){
                _updatingItems = false;
                if(rs.error){
                    console.log(rs.msg); //error
                }else{
                    $grid.attr({'data-page':rs.result.page});
                    $("#loadmore").text("Load More").blur();
                    if(parseInt(rs.result.page,10) < parseInt(rs.result.totalpage,10)){
                        //not last page
                        $("#loadmore").css({display:"inline-block"});
                    }else{
                        //last page
                        $("#loadmore").css({display:"none"});
                    }

                    //add to masonry
                    $.each(rs.result.data, function(index,value){
                        var $temp = createIndexGridItem(value);
                        $grid.append( $temp );
                         ScrollReveal().reveal($temp, slideUp);
                    });

                    $(".total_iger").text(rs.result.total);
                    $(".story_tell_cont").removeAttr("style");
                }
            },
            dataType: "json"

        });
    }
    $("#loadmore").click(function(e){
        e.preventDefault();
        if($(".items.grid").attr("data-nc_data") == "post"){
            updatePostItems();
        }else{
            updateItems();
        }
    });

    /*
    $(window).scroll(function(){
        if($("#loadmore:visible").length){
            var $pos = $("#loadmore").offset();
            if($pos && $(window).scrollTop() > $pos.top - $(window).height()){
                $("#loadmore").click();
            }
        }
    });  
    */
});