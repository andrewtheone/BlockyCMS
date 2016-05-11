Dropzone.autoDiscover = false;
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

var initImageListField = function(container) {

	var elements = $(container).find(".elements input");
	var self = container;


	var _remove = function(index) {

		var elements = $(self).find(".elements input");
		var toRemove = $(self).find(".elements input[data-image-index='"+index+"']");

		toRemove.each(function() {
			$(this).remove();
		});

		/*var length = 0;
		if(elements.length > 1) {
			length = (elements.length-1)/3;
		}*/

		var ii = 0;
		$(self).find(".elements input[data-image-index]").each(function() {
			var newID = parseInt(ii/3);
			$(this).attr("name", $(this).attr("name").replace( $(this).attr("data-image-index"), newID ) );
			$(this).attr("data-image-index", newID);
			ii++;
		})

		initializeImages();
	}

	var dropzoneDiv = container.find(".dropzone");
	var dropzone = dropzoneDiv.dropzone({
		url: container.attr('data-dz-url'),
		success: function(a, data, c) {
			var data = JSON.parse(data);

			var elements = $(self).find(".elements input");
			var temp = $(self).find("[data-template]").clone();
			temp.removeAttr("data-template");

			var length = 0;
			if(elements.length > 1) {
				length = (elements.length-1)/3;
			}
			temp.attr('name', temp.attr('data-name').replace("_id_", length));
			var src = temp.clone();
			src.attr({
				"data-image-path": 'true',
				"data-image-index": length,
				"name": src.attr("name").replace("_type_", "path"),
				"value": container.attr('data-files-url')+data.path
			})

			var title = temp.clone();
			title.attr({
				"data-image-title": 'true',
				"data-image-index": length,
				"name": title.attr("name").replace("_type_", "title"),
				"value": ""
			})

			var alt = temp.clone();
			alt.attr({
				"data-image-alt": 'true',
				"data-image-index": length,
				"name": alt.attr("name").replace("_type_", "alt"),
				"value": ""
			})

			$(self).find(".elements").append(src);
			$(self).find(".elements").append(title);
			$(self).find(".elements").append(alt);

			initializeImages();
			/*$(container).find("[data-dz-thumbnail]").each(function() {
				if($(this).attr('data-index'))
					return;

				$(this).attr('data-index', length);
				$(this).click(function() {
					_remove(length);
				})
			})*/
		}
	})

	var initializeImages = function() {
		var elements = $(self).find(".elements input");
		dropzoneDiv.get(0).dropzone.removeAllFiles();
		for(var i = 0; i < (elements.length-1)/3; i++) {
			var src = $(container).find("input[data-image-path][data-image-index='"+i+"']").val();
			/*var title = $(this).find("input[data-image-title data-image-index='"+i+"']").val();
			var alt = $(this).find("input[data-image-alt data-image-index='"+i+"']").val();*/

			var file = {
			    name: src,
			    size: 0,
			    status: Dropzone.ADDED,
			    accepted: true
			};
			dropzoneDiv.get(0).dropzone.emit("addedfile", file);                                
			dropzoneDiv.get(0).dropzone.emit("thumbnail", file, src);
			dropzoneDiv.get(0).dropzone.emit("complete", file);
			dropzoneDiv.get(0).dropzone.files.push(file);

			$(container).find("[data-dz-thumbnail]").each(function() {
				if($(this).attr('data-index'))
					return;

				$(this).attr('data-index', i);

				$(this).click(function() {

					var i = $(this).attr('data-index');
					var self = $(this);


					var modal = $(".image-list-modal").clone().removeClass("image-list-modal").addClass("image-list-clone-modal");
					$("body").append(modal);
					modal.on('hidden.bs.modal', function () {
					    $(this).remove();
					})
					var imageData = {
						path: $(container).find(".elements input[data-image-path][data-image-index='"+i+"']").val() || '',
						title: $(container).find(".elements input[data-image-title][data-image-index='"+i+"']").val() || '',
						alt: $(container).find(".elements input[data-image-alt][data-image-index='"+i+"']").val() || '',
						id: i
					};


					modal.find("[data-image-path]").attr("src", imageData.path);
					modal.find("[data-image-title]").attr("value", imageData.title);
					modal.find("[data-image-alt]").attr("value", imageData.alt);

					modal.find("[data-image-remove]").click(function() {
						_remove( imageData.id );
						modal.modal('hide');
					});
					
					modal.find("[data-image-save]").click(function() {
						$(container).find("input[data-image-title][data-image-index='"+imageData.id+"']").val( modal.find("[data-image-title]").val() );
						$(container).find("input[data-image-alt][data-image-index='"+imageData.id+"']").val( modal.find("[data-image-alt]").val() );
						modal.modal('hide');
					});

					modal.modal('show');
				})
			})

			$(container).find(".dz-details").remove();
		}
	}

	initializeImages();
}

var initFileField = function(container) {
	var dropzoneDiv = container.find(".dropzone");
	var dropzone = dropzoneDiv.dropzone({
		url: container.attr('data-dz-url'),
		success: function(a, data, c) {
			var data = JSON.parse(data);
			container.addClass("has-image");
			container.find("[data-file-path-show]").attr('value', container.attr('data-files-url')+data.path);
			container.find("[data-file-path]").val(data.path);
			container.find("[data-file-name]").val(data.name);
			this.removeAllFiles();
		}
	})

	container.find("[data-remove]").click(function() {
		container.find("[data-file-path]").val('');
		container.removeClass('has-image');
		return false;
	})
}
var initFileListField = function(container) {

	var elements = $(container).find(".elements input");
	var self = container;


	var _remove = function(index) {

		var elements = $(self).find(".elements input");
		var toRemove = $(self).find(".elements input[data-file-index='"+index+"']");

		toRemove.each(function() {
			$(this).remove();
		});

		/*var length = 0;
		if(elements.length > 1) {
			length = (elements.length-1)/3;
		}*/

		var ii = 0;
		$(self).find(".elements input[data-file-index]").each(function() {
			var newID = parseInt(ii/2);
			$(this).attr("name", $(this).attr("name").replace( $(this).attr("data-file-index"), newID ) );
			$(this).attr("data-file-index", newID);
			ii++;
		})

		initializeFiles();
	}

	var dropzoneDiv = container.find(".dropzone");
	var dropzone = dropzoneDiv.dropzone({
		url: container.attr('data-dz-url'),
		success: function(a, data, c) {
			var data = JSON.parse(data);

			var elements = $(self).find(".elements input");
			var temp = $(self).find("[data-template]").clone();
			temp.removeAttr("data-template");

			var length = 0;
			if(elements.length > 1) {
				length = (elements.length-1)/2;
			}
			temp.attr('name', temp.attr('data-name').replace("_id_", length));
			var src = temp.clone();
			src.attr({
				"data-file-path": 'true',
				"data-file-index": length,
				"name": src.attr("name").replace("_type_", "path"),
				"value": data.path
			})

			var name = temp.clone();
			name.attr({
				"data-file-name": 'true',
				"data-file-index": length,
				"name": name.attr("name").replace("_type_", "name"),
				"value": data.name
			})

			$(self).find(".elements").append(src);
			$(self).find(".elements").append(name);

			initializeFiles();
		}
	})

	var initializeFiles = function() {
		var elements = $(self).find(".elements input");
		dropzoneDiv.get(0).dropzone.removeAllFiles();
		for(var i = 0; i < (elements.length-1)/2; i++) {
			var src = $(container).find("input[data-file-path][data-file-index='"+i+"']").val();

			var file = {
			    name: src,
			    size: 0,
			    status: Dropzone.ADDED,
			    accepted: true
			};
			dropzoneDiv.get(0).dropzone.emit("addedfile", file);                                
			dropzoneDiv.get(0).dropzone.emit("thumbnail", file, "https://i.vimeocdn.com/portrait/6130169_300x300.jpg");
			dropzoneDiv.get(0).dropzone.emit("complete", file);
			dropzoneDiv.get(0).dropzone.files.push(file);

			$(container).find("[data-dz-thumbnail]").each(function() {
				if($(this).attr('data-index'))
					return;

				$(this).attr('data-index', i);

				$(this).click(function() {

					var i = $(this).attr('data-index');
					var self = $(this);


					var modal = $(".file-list-modal").clone().removeClass("file-list-modal").addClass("file-list-modal-clone");
					$("body").append(modal);
					modal.on('hidden.bs.modal', function () {
					    $(this).remove();
					})
					var fileData = {
						path: $(container).find(".elements input[data-file-path][data-file-index='"+i+"']").val() || '',
						name: $(container).find(".elements input[data-file-name][data-file-index='"+i+"']").val() || '',
						id: i
					};


					modal.find("[data-file-path]").attr("value", fileData.path);
					modal.find("[data-file-name]").attr("value", fileData.name);

					modal.find("[data-file-remove]").click(function() {
						_remove( fileData.id );
						modal.modal('hide');
					});
					
					modal.find("[data-file-save]").click(function() {
						$(container).find("input[data-file-name][data-file-index='"+fileData.id+"']").val( modal.find("[data-file-name]").val() );
						modal.modal('hide');
					});

					modal.modal('show');
				})
			})

			$(container).find(".dz-details").remove();
		}
	}

	initializeFiles();
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
		template.find("[data-field-type='file']").each(function() {
			initFileField($(this))
		})
		initRepeatable(template);
		initSelect2Field(template)
		initTagField(template)
		template.find("[data-field-type='image']").each(function() {
			initGridField( $(this) )
		})
		initHtmlField(template)
		return false;
	})

	container.find('[data-repeatable]').each(function() {
		initRepeatable($(this))
		initSelect2Field($(this))
		initTagField($(this))
		//initGridField($(this))
		initHtmlField($(this));
	})
}

var initSelect2Field = function(container) {
	container.find("select").each(function() {
		if($(this).attr('data-tag') == "1")
			return;
		var parent = $(this).closest("[data-field-type='select']");
		if(parent.attr('data-ajax') && (parent.attr('data-ajax') == 'true')) {
			console.log("ajax driven");

			var ct = $(this).attr('data-contenttype');
			var key = $(this).attr('data-key');
			var value = $(this).attr('data-value');
			$(this).select2({
				ajax: {
					url: '/admin/select2?contenttype='+ct+'&key='+key+'&value='+value,
			        processResults: function (data) {
			        	data = JSON.parse(data);
			            return {
			                results: $.map(data.items, function (item) {
			                    return {
			                        text: item.text,
			                        id: item.id
			                    }
			                })
			            };
			        }
				}
			})
			return;
		}
		$(this).select2();
	})
}

var initTagField = function(container) {
	container.find("select").each(function() {
		if($(this).attr('data-tag') != "1")
			return;
		$(this).select2({
			tag: true,
			tags: true
		});
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

var isInitable = function(container) {
	var parentForm = container.closest("form");
	var i = parentForm.attr('data-field-init');

	if(i == null)
		return true;

	if(parseInt(i) == 1)
		return true;
	return false;
}

$(document).ready(function() {

	$("[data-field-type='image']").each(function() {
		if(isInitable($(this)))
			initImageField( $(this) )
	})

	$("[data-field-type='imagelist']").each(function() {
		if(isInitable($(this)))
			initImageListField( $(this) )
	})

	$("[data-field-type='file']").each(function() {
		if(isInitable($(this)))
			initFileField( $(this) )
	})

	$("[data-field-type='filelist']").each(function() {
		if(isInitable($(this)))
			initFileListField( $(this) )
	})

	$("[data-field-type='repeater']").each(function() {
		if(isInitable($(this)))
			initRepeaterField( $(this) )
	})

	$("[data-field-type='select']").each(function() {
		if(isInitable($(this)))
			initSelect2Field($(this))
	})

	$("[data-field-type='tag']").each(function() {
		if(isInitable($(this)))
			initTagField($(this))
	})

	$("[data-field-type='grid']").each(function() {
		if(isInitable($(this)))
			initGridField($(this))
	})

	$("[data-field-type='html']").each(function() {
		if(isInitable($(this)))
			initHtmlField($(this))
	})

	$("[data-field-type='selectLocale']").each(function() {
		if(isInitable($(this)))
			initSelectLocale($(this))
	})

	$("[data-field-type='searchAll']").each(function() {
		if(isInitable($(this)))
			initSearchAll($(this));
	})
})