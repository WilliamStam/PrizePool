/*
 * Date: 2013/05/16 - 9:41 AM
 */
$(document).ready(function () {
	
	
	$(document).on('click', '.btn-row-details', function (e) {
		var $this = $(this), $table = $this.closest("table");
		var $clicked = $(e.target).closest("tr.btn-row-details");
		var active = true;

		if ($this.hasClass("active") && $clicked) active = false;

		$("tr.btn-row-details.active", $table).removeClass("active");
		if (active) {
			$this.addClass("active");
		}

		var show = $("tr.btn-row-details.active", $table).nextAll("tr.row-details");

		$("tr.row-details", $table).hide();
		if (show.length) {
			show = show[0];
			$(show).show();
		}

	});
	$('body').tooltip({
		selector : '*[rel=tooltip]',
		live     : true,
		container: 'body'

	}).popover({
			selector : '*[rel=popover]',
			offset   : 5,
			live     : true,
			container: 'body',
			html     : true
		});

	
	hashChange();
	$(window).bind('hashchange', function (e) {
		hashChange();
	});

});

function hashChange() {
	var scrollTo = $.bbq.getState("scrollTo");
	if ($(scrollTo).length) {
		$.smoothScroll({
			scrollTarget: scrollTo
		});
	}
//console.log(scrollTo);

}
function file_size(size) {
	if (!size) {
		return 0;
	}
	var origSize = size;
	var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
	var i = 0;
	while (size >= 1024) {
		size /= 1024;
		++i;
	}

	if (origSize > 1024) {
		size = size.toFixed(1)
	}
	return size + ' ' + units[i];
}

function updatetimerlist(d, page_size) {
	//d = jQuery.parseJSON(d);

	if (!d || !typeof d == 'object') {
		return false;
	}

	var data = d['timer'];
	var page = d['page'];
	var models = d['models'];
//console.log(d['user'])
	$("#navbar-info").jqotesub($("#template-navbar-info"), d['user']);

	var pageSize = (page && page['size']) ? page['size'] : page_size;

	if (data) {
		var highlight = "";
		if (page['time'] > 0.5)    highlight = 'style="color: red;"';

		var th = '<tr class="heading" style="background-color: #fdf5ce;"><td >' + page['page'] + ' : <span class="g">Size: ' + file_size(pageSize) + '</span></td><td class="s g"' + highlight + '>' + page['time'] + '</td></tr>', thm;
		if (models) {
			thm = $("#template-timers-tr-models").jqote(models, "*");
		} else {
			thm = "";
		}
		//console.log(thm)

		$("#systemTimers").prepend(th + $("#template-timers-tr").jqote(data, "*") + thm);

		// console.log($("#systemTimers").prepend(th + $("#template-timers-tr").jqote(data, "*")));
	}

}
function quote_to_single(str){
	str = str.replace(/"/g,"'");

	return str;
}
function in_array(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}
Number.prototype.formatMoney = function(c, d, t){
	var n = this,
		c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};