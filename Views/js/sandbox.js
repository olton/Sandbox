var Sandbox = {

    sortCodes: {
        field: "name",
        dir: "asc"
    },

    init: function(){
        this.version = "1.0.0";

        Metro.storage.setKey("M4:SANDBOX");

        return this;
    },

    ver: function(){
        return this.version;
    },

    info: function(title, message, type){
        Metro.infobox.create("<h3>"+title+"</h3><div>"+message+"</div>", type);
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
        $.post(
            route,
            data,
            function(response){
                if (!response.result) {
                    Metro.utils.exec(cb_error, [response]);
                    Sandbox.info("Error", response.message);
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
                    Editors.saved = true;
                }

                iframe.src = response.data.iframe;

                Metro.utils.exec(cb_success, [response]);

                $("#iframe_reload").hide();
            }, function (response) {
                Metro.utils.exec(cb_error, [response]);
                $("#iframe_reload").hide();
            });
        });
    },

    login: function(f){
        var that = this;
        var form = $(f);
        $("#activity").css("visibility", "visible");
        form.addClass("disabled");
        setTimeout(function(){
            that.sendForm(f, "/login/process", "/", null, function(response){
                form.removeClass("disabled");
                $("#activity").css("visibility", "hidden");
                that.info("Error!", response.message);
            })
        }, 100);
    },

    signup: function(f){
        var that = this;
        var form = $(f);
        $("#activity").css("visibility", "visible");
        form.addClass("disabled");
        setTimeout(function(){
            that.sendForm(f, "/signup/process", "/", null, function(response){
                form.removeClass("disabled");
                $("#activity").css("visibility", "hidden");
                that.info("Error!", response.message);
            })
        }, 100);
    },

    logout: function(){
        this.go("/logout/process");
    },

    open: function(url, target){
        if (!Metro.utils.isValue(target)) {
            target = "_self";
        }
        var win = window.open(url, target);
        win.focus();
    },

    delete: function(hash){

    },

    sortList: function(obj, col){
        if (Sandbox.sortCodes.field === col) {
            if (Sandbox.sortCodes.dir === "asc") {
                Sandbox.sortCodes.dir = "desc";
            } else {
                Sandbox.sortCodes.dir = "asc";
            }
        } else {
            Sandbox.sortCodes.field = col;
            Sandbox.sortCodes.dir = "asc";
        }
        $("#sort-by").text(String(col).capitalize());
        $(obj).data("list").sorting(Sandbox.sortCodes.field, Sandbox.sortCodes.dir, true);
    }
};

Sandbox.init();

window.on_page_functions.forEach(function(func){
    Metro.utils.exec(func, []);
});