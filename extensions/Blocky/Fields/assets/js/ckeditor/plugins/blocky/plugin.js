CKEDITOR.plugins.add( 'blocky', {
    icons: 'blocky',
    init: function( editor ) {

            editor.ui.addRichCombo('blocky', {
                label: "Blocky",
                className: 'myClassname',
                showAll: function () { buildList(this); },
                panel:
                {
                },

                init: function () {
                    var rebuildList = CKEDITOR.tools.bind(buildList, this);
                    rebuildList();
                    $(editor).bind('rebuildList', rebuildList);
                },

                onClick: function (id) {
                    //openIFrame(id, this);
                }

            });

            var buildList = function () {

                var self = this;

                $.getJSON("/admin/getCKEditorWidgets", function(resp) {
                    resp.map(function(i, index) {
                        self.add(i.label, '', i.name);
                    })
                    self.commit();
                });

            };

            //var triggerBuildList = new function(editor){ $(editor).trigger('rebuildList'); };

       /* $.getJSON("/admin/getCKEditorWidgets", function(resp) {

            resp.map(function(i, index) {
                console.log(i);
                console.log(editor);
                window.v = editor;
                editor.addCommand( i.command, new CKEDITOR.dialogCommand( i.dialog ));

                editor.ui.addButton( i.name, {
                    label: i.label,
                    command: i.command,
                    toolbar: 'blocky'
                });

            })
        })

        CKEDITOR.dialog.add( 'selectContent', this.path + 'dialogs/selectContent.js' );*/
    }
});