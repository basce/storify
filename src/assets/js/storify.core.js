var storify = storify || {};
storify.core = storify.core || {};

//display, debug
storify.core.debug = function(){
	if(window.location.hostname.search("staging") !== -1){
		if(arguments.length == 2){
			console.log(arguments[0], arguments[1]);
		}else{
			console.log(arguments[0]);
		}
	}
}

storify.core._funcs = [];
storify.core.addListener = function(type, func){
	storify.core._funcs.push({
		type:type,
		func:func
	});
}

storify.core.call = function(type, data, callback){
	if(storify.core._funcs.length){
		var found = false;
		storify.core._funcs.forEach(function(item, index){
			//check if any item match any register type
			if(item.type == type){
				found = true;
				var result = item.func.call(null, data);
				if(callback){
					callback(result);
				}
				storify.core.debug("type :"+type+", found, data return", result);
			}
		});
		if(found){
			storify.core.debug("type :"+type+", no found");
		}
	}else{
		storify.core.debug("type :"+type+", no found, zero registered listerner");
	}
}

storify.core.leadingZero = function(str, size){
	return ('000000000' + str).substr(-size);
}

storify.core.formatMoney = (n,c,d,t)=>{
	var c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;

  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + "";// (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

//get data

storify.core._gettingProjectListing = false;
storify.core.getProjectListing = ( grid_element_id, load_button_element_id, empty_message, projectlistfunc, onError, onComplete)=>{
	if(storify.core._gettingProjectListing) return;
	storify.core._gettingProjectListing = true;

	var $grid = $(grid_element_id),
		cur_page = parseInt($grid.attr("data-page"), 10),
		sort = $grid.attr("data-sort"),
		filter = $grid.attr("data-filter"),
		$load_button = $(load_button_element_id);

	cur_page = cur_page ? cur_page + 1 : 1;

	$load_button.html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
	$.ajax({
		method: "POST",
		dataType: "json",
		data: {
			method: "getProject",
			filter: filter,
			page: cur_page,
			sort: sort
		},
		success: function(rs){
			storify.core._gettingProjectListing = false;
			if(rs.error){
				if(onError) onError(rs);
			}else{
				$grid.attr({'data-page': rs.result.page});
				$load_button.text("Load More").blur();
				if(parseInt(rs.result.page, 10) < parseInt(rs.result.totalpage, 10)){
					$load_button.css({display:"inline-block"});
				}else{
					$load_button.css({display:"none"});
				}

				$.each(rs.result.data, function(index, value){
					var div = projectlistfunc(index, value);
					$grid.append(div);
					ScrollReveal().reveal(div, storify.slideUp);
				});

				$(".linkify").linkify({
					target: "_blank"
				});

				if(!+rs.result.total){
					$grid.append($("<p>").text(empty_message));
				}

				if(onComplete){
					onComplete();
				}
			}
		}
	});
}

storify.core.sendNotification = ( msg )=>{
	if("Notification" in window){
		if(Notification.permission === "granted"){
			var notification = new Notification( msg );
		}else{
			console.log("user denied notifcation");
		}
	}else{
		console.log("browser doesn't support notification");
	}
}