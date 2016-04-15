(function ( $ ) {
	$.fn.serializeObject = function()
	{
	    var o = {};
	    var a = this.serializeArray();
	    $.each(a, function() {
	        if (o[this.name] !== undefined) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    return o;
	};

 	var simpleHandler = function(form) {
 		this.form = form;
 		this.name = name;

 		this.form.bind("submit", this.onSubmit.bind(this));
 	}

 	simpleHandler.prototype.onSubmit = function() {

 		$.ajax(this.form.attr('action'), {
 			data: this.form.find("input, select, textarea").serializeObject(),
 			type: 'post',
 			dataType: 'json',
 			success: this.onResponse.bind(this)
 		})
 		return false;
 	}

 	simpleHandler.prototype.onResponse = function(resp) {
 		if(resp.success == 0) {
 			for(var i in resp.errors) {
 				var key = resp.errors[i].field;
 				var msg = resp.errors[i].message;
 				this.form.find("[name='form_data["+key+"]']").each(function() {
 					$(this).parent().find(".error").html(msg);
 				})
 			}
 		} else {
 			alert("sikeres form kitöltés");
 			this.form.trigger("reset");
 		}
 	}

 	simpleHandler.prototype.onKeyDown = function() {

 	}

 	simpleHandler.prototype.unregister = function() {
 		this.form.unbind("submit");
 	}

 	var ajaxForm = function() {
 		this.handlers = {
 			'simpleHandler': simpleHandler
 		};

 		this.forms = [];

 		var self = this;
 		$("form[data-form-ajax]").each(function() {
			self.forms.push({
				form: $(this),
				handlerName: $(this).attr("data-handler"),
				handler: new simpleHandler($(this))
			})
 		})
 	}

 	ajaxForm.prototype.extend = function(handlerName, handler) {
 		var customHandler = simpleHandler;

 		$.extend(customHandler.prototype, handler);

 		this.handlers[handlerName] = customHandler;

 		this.registerHandler(handlerName);
 	}

 	ajaxForm.prototype.registerHandler = function(handlerName) {
 		var self = this;

 		this.forms.map(function(form, i) {

 			if(form.handlerName == handlerName) {
 				form.handler.unregister();

 				form.handler = new self.handlers[handlerName](form.form);

 				self.forms[i] = form;
 			}
 		})
 	}

    $.fn.ajaxForm = new ajaxForm();
 
}( jQuery ));