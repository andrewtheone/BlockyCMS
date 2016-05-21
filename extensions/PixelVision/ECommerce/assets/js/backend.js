$(document).ready(function() {

	var initPropertiesField = function(container) {
		var addButton = container.find("[data-add]");
		var tableBody = container.find("table");
		var template = container.find("[data-template]").val();
		var elements = container.find("[data-elements] input");

		var id = tableBody.find("tr").length+1;

		addButton.click(function() {
			var t = template.replace("_id_", id).replace("_id_", id).replace("_attr_", '').replace("_value_", '');
				
			t = $(t);
			t.insertAfter( $( tableBody.find("tr").last() ) )
			//tableBody.append(t);
			t.find("[data-remove]").click(function() {
				t.closest("tr").remove();
			});
			id++;
			initSelects();
			return false;
		})
		container.find("tr [data-remove]").click(function() {
				$(this).closest("tr").remove();
		});
		var initSelects = function() {
			container.find("select").each(function() {
				var element = $(this);

				if( $(this).attr('inited') )
					return;
				$(this).attr('inited', true);
				
				var lastResults = [];
				console.log(container.attr('data-url'));
				element.select2({
				    ajax: {
				        multiple: false,
				        url: container.attr('data-url'),
				        dataType: "json",
				        type: "POST",
				        data: function (term, page) {
				            return {
				            	type: element.attr('data-type'),
				                q: term
				            };
				        },
				        results: function (data, page) {
				            lastResults = data.results;
				            return data;
				        },
				        processResults: function (data) {
				        	//var data = JSON.parse(data);
				            return {
				                results: $.map(data.items, function (item) {
				                    return {
				                        text: item.text,
				                        id: item.id
				                    }
				                })
				            };
				        }
				    },
				    createSearchChoice: function (term) {
				        if(lastResults.some(function(r) { return r.text == term })) {
				            return { id: term, text: term };
				        }
				        else {
				            return { id: term, text: term };
				        }
				    }
				});
			})
		}

		initSelects();
	}

	$("[data-field-type='properties']").each(function() {
		initPropertiesField($(this));
	})
})