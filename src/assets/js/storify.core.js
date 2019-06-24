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