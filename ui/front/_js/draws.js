$(document).ready(function(){
	
	loadPage();
	$(document).on("reset","#form-record",function(e){
		$.bbq.removeState("ID");
		loadPage();
		
	});
	$(document).on("click","table.records .record",function(e){
		var $this = $(this);
		$.bbq.pushState({"ID":$this.attr("data-id")});
		loadPage();
		
	});
	
	
	
	
	
	$(document).on("submit", "#form-record", function (e) {
		e.preventDefault();
		var $this = $(this);
		var data = $this.serialize();

		var ID = $.bbq.getState("ID");
		$(".loadingmask").show();
		$.post("/save/draws/details/?ID=" + ID, data, function (r) {
			r = r['data'];
			if ($.isEmptyObject(r['error'])) {
				$.bbq.removeState("ID");
				loadPage();
				//alert("Successfully Captured!")

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
	$(document).on("click",".btn-draw-form",function(e){
		var $this = $(this);
		e.preventDefault();

		$.getData("/data/draws/form?ID="+$this.attr("data-id"), {}, function (data) {
			var $modal = $("#modal-draw-form");

			$modal.jqotesub($("#template-form-draw"), data).modal("show");
			$(".loadingmask").fadeOut();
		});

	});


	$(document).on("submit", "#form-draw-form", function (e) {
		e.preventDefault();
		var $this = $(this);
		var data = $this.serialize();

		var ID = $("#form-draw-form #ID").val();
		$(".loadingmask").show();
		$.post("/save/draws/form/?ID=" + ID, data, function (r) {
			r = r['data'];
			if ($.isEmptyObject(r['error'])) {
				$("#modal-draw-form").modal("hide");
				loadPage();
				//alert("Successfully Captured!")

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
	var ID = $.bbq.getState("ID")||'';
	$.getData("/data/draws/details?ID="+ID, {}, function (data) {
		var $form_area = $("#work-area");
		//console.log(data.member)
		
		$form_area.jqotesub($("#template-form"), data);
		//setTimeout(function(){$form_area.find("#phone").focus();},300)
		
		
		
		$loadingmask.fadeOut();
	});
	
	
}
