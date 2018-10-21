var Sandbox = {
    init: function(){
        this.version = "1.0.0";

        Metro.storage.setKey("M4:SANDBOX");

        return this;
    },

    ver: function(){
        return this.version;
    },

    message: function(title, text){
        Metro.dialog.create({
            title: title,
            content: "<div>"+text+"</div>"
        });
    },

    go: function(target){
        document.location.href = target;
        return false;
    },

    reload: function(){
        document.location.reload();
        return false;
    },

    sendForm: function(form, route, next, cb_ok, cb_error){
        this.sendData(
            $(form).serialize(),
            route,
            next,
            cb_ok,
            cb_error
        )
    },

    sendData: function(data, route, next, cb_ok, cb_error){
        var that = this;
        // Show activity;
        $.post(
            route,
            data,
            function(response){
                // Hide activity
                if (!response.result) {
                    that.message("Внимание", response.message);
                    Metro.utils.exec(cb_error, [response]);
                    return false;
                }

                Metro.utils.exec(cb_ok, [response]);

                if (Metro.utils.isValue(next)) {
                    that.go(next);
                }
            }
        );
    },

    editTimer: function(){
        Sandbox.saveCode(false, function(response){

        });
    },

    openTitleEditor: function(){
        $('#code-title-label').hide();
        $('#code-title-editor').show().find("input").focus();
    },

    saveTitle: function(){
        Sandbox.saveCode(false, function(response){
            $('#code-title-label').show();
            $('#code-title-editor').hide();
        });
    },

    saveCode: function(save_button, cb_success, cb_error){
        var that = this, iframe = document.getElementById("iframe");

        $("#iframe_reload").show(function(){
            var data = {
                id: $('#code_id').val(),
                title: $('#code_title').val(),
                hash: $('#code_hash').val(),
                template: $('#template').val(),
                temp_file: $('#temp_file').val(),
                saved: $('#saved').val(),
                can_save: save_button,

                html: Editors.getEditorValue('html_editor'),
                css: Editors.getEditorValue('css_editor'),
                js: Editors.getEditorValue('js_editor')
            };

            that.sendData(data, "/code/save", null, function(response){
                $('#console-output').html("");
                $('#code_title').val(response.data.title);
                $('#code_title_label').text(response.data.title);

                if (response.data.mode !== "temp") {
                    history.pushState({},"Goto saved code", response.data.url);
                    $('#code_id').val(response.data.id);
                    $('#code_hash').val(response.data.hash);
                    $('#temp_file').val(response.data.temp_file);
                    $('#saved').val(1);
                }

                iframe.src = response.data.iframe;

                Metro.utils.exec(cb_success, [response]);

                $("#iframe_reload").hide();
            }, function (response) {
                Metro.utils.exec(cb_error, [response]);
                $("#iframe_reload").hide();
            });
        });

    }
};

Sandbox.init();

window.on_page_functions.forEach(function(func){
    Metro.utils.exec(func, []);
});