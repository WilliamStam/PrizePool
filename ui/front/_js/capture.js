$(document).ready(function(){
	
	loadPage();
	$(document).on("reset","#form-record",function(e){
		$.bbq.removeState("search");
		loadPage();
		
	});
	$(document).on("change keyup","#num_tickets",function(e){
		$("#slider").slider({"value":$(this).val()});
		total();
	});
	$(document).on("click",".change-num-tickets",function(e){
		e.preventDefault();
		var change = $(this).attr("data-val");
		var value = $("#num_tickets").val();
		
	
		
		$("#num_tickets").val(eval(value + change)).change();
		sliderBtns();
		total();
		//$("#slider").slider({"value":$(this).val()});
		return false;
	});
	
	
	
	
	$(document).on("submit","#form-search",function(e){
		e.preventDefault();
		$.bbq.pushState({"search":$("#phone").val()})
		loadPage();
		return false;
	});
	$(document).on("submit", "#form-record", function (e) {
		e.preventDefault();
		var $this = $(this);
		var data = $this.serialize();

		var ID = $.bbq.getState("ID");
		$(".loadingmask").show();
		$.post("/save/capture/form/?ID=" + ID, data, function (r) {
			r = r['data'];
			if ($.isEmptyObject(r['error'])) {
				$.bbq.removeState("ID");
				$.bbq.removeState("search");
				loadPage();
				alert("Successfully Captured!")

			} else {
				//var result = $.parseJSON(r.error);
				$.each(r.error, function(k, v) {
					$("#"+k,$this).after('<div class="help-inline error-text">'+v+'</div>').closest(".control-group").addClass("error");
				});
				
				
				
				$this.find("button[type='submit']").addClass("btn-danger");
				$(".loadingmask").fadeOut();
			
				
			}

		});
		return false;
	});
});

function loadPage(){
	var $loadingmask = $(".loadingmask").show();
	var search = $.bbq.getState("search");
	$.bbq.removeState("ID");
	$.getData("/data/capture/form", {"search":search}, function (data) {
		var $form_area = $("#form-area");
		//console.log(data.member)
		if (data['search']){
			if (data.member.ID) {
				$.bbq.pushState({"ID":data.member.ID})
			} else {
				data.member.phone = search;
			}
			$form_area.jqotesub($("#template-record"), data);
			if (data.member.ID!=""){
				setTimeout(function(){$form_area.find("#num_tickets").focus().select();},500)
			} else {
				setTimeout(function(){$form_area.find("#fullname").focus();},500)
			}
			

			slider();
		} else {
			$form_area.jqotesub($("#template-search"), {});
			setTimeout(function(){$form_area.find("#phone").focus();},300)
		}
		
		
		$loadingmask.fadeOut();
	});
	
	
}

function slider(){

	$("#slider").slider({
		range: "min",
		value: 1,
		min: 1,
		max: 20,
		slide: function( event, ui ) {
			$( "#num_tickets" ).val( ui.value );
			total();
		}
	});
	$( "#num_tickets" ).val( $( "#slider" ).slider( "value" ) );

	sliderBtns();
	total();
}

function sliderBtns(){
	var $down = $(".change-num-tickets[data-val='-1']");
	if ($( "#num_tickets" ).val()<=1){
		$down.attr("disabled","disabled");
	} else {
		$down.removeAttr("disabled");
	}
	
}
function total(){
	var num = $( "#num_tickets" ).val();
	var ticketPrice = _price;
	
	var cur = num * ticketPrice;
	
	$("#totalValue").html("R "+ cur.formatMoney(2, '.', ' '));
	
	
}