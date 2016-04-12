
var initImageField = function(container) {
	var dropzoneDiv = container.find(".dropzone");
	var dropzone = dropzoneDiv.dropzone({
		url: container.attr('data-dz-url'),
		success: function(a, data, c) {
			var data = JSON.parse(data);
			container.addClass("has-image");
			container.find("[data-preview]").attr('src', container.attr('data-files-url')+data.path);
			container.find("[data-image-path]").val(data.path);
			this.removeAllFiles();
		}
	})

	container.find("[data-remove]").click(function() {
		container.find("[data-image-path]").val('');
		container.removeClass('has-image');
		return false;
	})
}

var initRepeatable = function(repeatable) {
	var removeButton = repeatable.find('[data-repeatable-remove]').click(function() {
		$(this).closest('[data-repeatable]').remove();
		return false;
	})

	var moveUpButton = repeatable.find('[data-move-up]').click(function() {
		$(this).closest('[data-repeatable]').insertBefore($(this).closest('[data-repeatable]').prev());
		return false;
	})

	var moveDownButton = repeatable.find('[data-move-down]').click(function() {
		$(this).closest('[data-repeatable]').insertAfter($(this).closest('[data-repeatable]').next());
		return false;
	})

	var collapseButton = repeatable.find("[data-collapse-child-toggle]").click(function() {

		var panel = $(this).closest('[data-repeatable] > .panel');

		if(panel.hasClass('collapsed')) {
			$(this).html('-');
			panel.removeClass('collapsed');
		} else {
			$(this).html('+');
			panel.addClass('collapsed');
		}

		return false;
	});
};

var initRepeaterField = function(container) {
	$("div[data-repeatable-template][data-plain]").each(function() {
		var t = $("<textarea data-repeatable-template data-repeat-name='"+$(this).attr('data-repeat-name')+"'>");
		t.css("display", "none");
		t.val($(this).html());
		t.insertAfter($(this))
		$(this).remove();
	})


	var collapseButton = container.find("[data-collapse-toggle]").each(function() {
		if( $(this).attr('data-has-collapse-fn') )
			return;
		$(this).attr('data-has-collapse-fn', true);
		$(this).click(function() {
			var repeatName = $(this).attr('data-repeat-name');
			var panel = $(".panel.panel-repeater[data-repeat-name='"+repeatName+"']");

			if(panel.hasClass('collapsed')) {
				$(this).html('-');
				panel.removeClass('collapsed');
			} else {
				$(this).html('+');
				panel.addClass('collapsed');
			}
			return false;
		});
	});

	var addButton = container.find('[data-repeat]').click(function() {
		var repeatName = $(this).attr('data-repeat-name');
		var template = container.find('[data-repeatable-template]');
		if( template.attr('data-repeat-name') != repeatName )
			return false;
		template = template.val();

		var lengths = 0;

		container.find('[data-repeatable]').each(function() {
			if($(this).attr('data-repeat-name') == repeatName)
				lengths++;
		});
		template = template.replace(/__BLOCKY_REPEATER_ID__/g, lengths);
		template = $(template);

		container.find('[data-repeat-place]').each(function() {
			if($(this).attr('data-repeat-name') != repeatName)
				return;
			template.insertBefore($(this));
		})
		$("div[data-repeatable-template][data-plain]").each(function() {
			var t = $("<textarea data-repeatable-template data-repeat-name='"+$(this).attr('data-repeat-name')+"'>");
			t.css("display", "none");
			t.val($(this).html());
			t.insertAfter($(this))
			$(this).remove();
		})
		//.insertBefore(template);
		template.find("[data-field-type='image']").each(function() {
			initImageField( $(this) )
		})
		initRepeatable(template);
		initSelect2Field(template)
		template.find("[data-field-type='image']").each(function() {
			initGridField( $(this) )
		})
		initHtmlField(template)
		return false;
	})

	container.find('[data-repeatable]').each(function() {
		initRepeatable($(this))
		initSelect2Field($(this))
		//initGridField($(this))
		initHtmlField($(this));
	})
}

var initSelect2Field = function(container) {
	container.find("select").each(function() {
		$(this).select2();
	})
}

var initGridField = function(container) {
	console.log(container);

	var _container = container.find("[data-grid-container]");
	var columns = _container.attr("data-grid-columns").split(', ');
	var data = JSON.parse(_container.attr("data-grid-data"));
	var valueStore = container.find("[data-grid-value]");
	if(data.length == 0) {
		data[0] = {};
		for(var i in columns)
			data[0][i] = '';
	}

	var hot = new Handsontable(_container[0],
	    {
	        autoColumnSize: true,
	        minSpareRows: 1,
	        width: '100%',
	        height: 100,
	        minCols: columns.length,
	        data: data,
	        colHeaders: columns,
	        columnSorting: true,
	        sortIndicator: true,
	        rowHeaders: true,
	        manualRowMove: true,
			contextMenu: ['row_above', 'row_below', 'remove_row'],
	        manualColumnResize: true,
	        afterRender: updateData,
	        afterColumnSort: updateData,
	        afterRowMove: updateData
	    });
    function updateData(changes, source) {
        var data = this.getData(0, 0, this.countRows() - 1, this.countCols() - 1); // Because #989
        data = data.filter(function(row) {
           var value = false;
           for(var i in row) {
           		if(row[i]) {
           			if((row[i].length > 0)) {
           				value = true;
           			}
           		}
           }
           return value;
        });
        valueStore.val(JSON.stringify(data));
    }
}

var initSelectLocale = function(container) {
	var input = container.find("select");
	input.select2();
	input.change(function() {
		var newval = $(this).val();
		window.location = window.location.pathname+"?locale="+newval;
	})
}

var initHtmlField = function(container) {
	container.find("[data-ckeditor-container]").ckeditor();
}

var initSearchAll = function(container) {
	var input = container.find("input");
	var cont = container.find(".search-autosuggestion-wrapper");

	input.focus(function(){
		if($(this).val().length < 4) return;

		cont.fadeIn();
	})

	input.keyup(function() {
		var val = $(this).val();
		if(val.length < 4)
			return;

		$.get($("body").attr('data-base-path')+"/admin/search?term="+val, function(resp) {
			cont.html(resp);
			cont.fadeIn();
		})
	})

	input.blur(function() {
		cont.fadeOut();
	})
}

$(document).ready(function() {

	$("[data-field-type='image']").each(function() {
		initImageField( $(this) )
	})

	$("[data-field-type='repeater']").each(function() {
		initRepeaterField( $(this) )
	})

	$("[data-field-type='select']").each(function() {
		initSelect2Field($(this))
	})

	$("[data-field-type='grid']").each(function() {
		initGridField($(this))
	})

	$("[data-field-type='html']").each(function() {
		initHtmlField($(this))
	})

	$("[data-field-type='selectLocale']").each(function() {
		initSelectLocale($(this))
	})

	$("[data-field-type='searchAll']").each(function() {
		initSearchAll($(this));
	})
})