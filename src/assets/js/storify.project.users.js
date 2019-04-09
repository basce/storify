var storify = storify || {};
storify.project = storify.project || {};

storify.project.charcount = function(input){
	var errorCnt = $(input).attr("data-error-cont"),
		strlength = $(input).attr("data-maxlength") ? $(input).attr("data-maxlength") : 0;

	if($(input).val()){
		if($(input).val().length < strlength){
			errorCnt.css({display:"none"});
		}else{
			errorCnt.css({display:"block"}).text("You have exceeded the character limit. Please shorten your caption.");
		}
	}else{
		errorCnt.css({display:"none"});
	}
};

storify.project.formatMoney = function(n, c, d, t) {
  var c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;

  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + "";// (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

storify.project.user = {
	data:null,
	_gettingUsers:false,
	getAllUser:function( callback ){
		if( !+storify.project._project_id ){
			console.log("storify.project._project_id not a number", storify.project._project_id);
			return;
		}
		if( storify.project.user._gettingUsers ) return;
		storify.project.user._gettingUsers = true;
		$.ajax({
			method: 	"POST",
			dataType: 	"json",
			data:{
				method: 	"getUsers",
				project_id: storify.project._project_id
			},
			success: function(rs){
				storify.project.user._gettingUsers = false;
				storify.project.user.data = rs.data;
				if( typeof callback === "function" ){
					callback();
				}
			}
		})
	},
	getUserDetail:function( user_id ){
		var user_obj = null,
			a = storify.project.user.data;
		if(a && a.length){
			$.each(a, function(index,value){
				if( value.user_id == user_id ){
					user_obj = value;
				}
			});
		}
		return user_obj;
	}
};