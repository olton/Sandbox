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

        var data = {
            id: $('#code_id').val(),
            title: $('#code_title').val(),
            hash: $('#code_hash').val(),

            template: $('#template').val(),
            temp_file: $('#temp_file').val(),
            saved: $('#saved').val(),
            alien: $('#alien').val(),
            can_save: save_button,

            html: Editors.getEditorValue('html_editor'),
            css: Editors.getEditorValue('css_editor'),
            js: Editors.getEditorValue('js_editor'),

            html_head: $('#html_head').val(),
            html_classes: $('#html_classes').val(),
            body_classes: $('#body_classes').val(),
            html_processor: $('#html_processor').val(),
            css_processor: $('#css_processor').val(),
            js_processor: $('#js_processor').val(),
            js_external: $('#js_external').val(),
            css_external: $('#css_external').val(),
            desc: $('#desc').val(),
            tags: $('#tags').val(),
            code_type: $('#code_type').val(),
            js_type: $('#js_type').val(),
            css_base: $('input[name=css_base]').val()
        };

        $("#iframe_reload").show(function(){

            that.sendData(data, "/code/save", null, function(response){

                var data = response.data;
                var code = data.code;

                if (!Metro.utils.isObject(data)) {
                    Sandbox.message("Server error!", data);
                }

                $('#console-output').html("");
                $('#code_title').val(code.title);
                $('#code_title_label').text(code.title);

                if (response.data.mode !== "temp") {

                    history.pushState({},"Goto saved code", data.url);

                    $('#code_id').val(code.id);
                    $('#code_hash').val(code.hash);
                    $('#temp_file').val(data.temp_file);
                    $('#saved').val(1);
                    $('#code_page-debug_button').attr("href", data.debug_url).parent().show();
                    $('#code_page-fork_button').parent().show();
                    Editors.saved = true;
                } else {

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

    saveProfile: function(f){
        Sandbox.sendForm(f, "/profile/save", null, function(response){
            Metro.toast.init({
                showTop: true,
                distance: 60,
                clsToast: "success"
            }).create("Profile save successfully!")
        }, function(response){
            Sandbox.info("Error!", response.message);
        })
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
        Sandbox.sendData({
            hash: hash
        }, "/code/delete", null, function(response){
            var list;
            if (response.result) {
                list = $("#code-items").data("list");
                list.deleteItem(hash);
                list.draw();
            }
        }, function(response){
            Sandbox.info("Delete error!", response instanceof String ? response : response.message);
        });
    },

    fork: function(hash){
        Sandbox.sendData({
            hash: hash
        }, "/code/fork/", null, function(response){
            Sandbox.go(response.data.redirect);
        }, function(response){
            Sandbox.info("Fork error!", response instanceof String ? response : response.message);
        });
    },

    sortList: function(obj, col, dir){
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

        if (Metro.utils.isValue(dir)) {
            Sandbox.sortCodes.dir = dir;
        }

        Metro.storage.setItem("CODE:LIST:SORT:FIELD", Sandbox.sortCodes.field);
        Metro.storage.setItem("CODE:LIST:SORT:DIR", Sandbox.sortCodes.dir);

        $("#sort-by").html(String(col).capitalize() + (Sandbox.sortCodes.dir === "asc" ? "<span class='mif-arrow-up'></span>" : "<span class='mif-arrow-down'></span>"));
        $(obj).data("list").sorting(Sandbox.sortCodes.field, Sandbox.sortCodes.dir, true);
    },

    addMetaTag: function(tag){
        var tags = {
            'viewport': '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">',
            'metro4:init': '<meta name="metro4:init" content="true">',
            'metro4:locale': '<meta name="metro4:locale" content="en-US">',
            'metro4:week_start': '<meta name="metro4:week_start" content="1">',
            'metro4:cloak': '<meta name="metro4:cloak" content="fade">',
            'metro4:cloak_duration': '<meta name="metro4:cloak_duration" content="500">'
        };
        var head = $("#html_head");
        head.val(head.val()+tags[tag]+"\n");
    },

    sendMessageToIframe: function(iframe, value){
        iframe.contentWindow.postMessage(value, '*');
    },

    expandBlock: function(el){
        if (el === 'html') {
            $('#html-code').css("flex-basis", "calc(100% - 8px)");
            $('#js-code').css("flex-basis", 0);
            $('#css-code').css("flex-basis", 0);
        }
        if (el === 'css') {
            $('#css-code').css("flex-basis", "calc(100% - 8px)");
            $('#js-code').css("flex-basis", 0);
            $('#html-code').css("flex-basis", 0);
        }
        if (el === 'js') {
            $('#js-code').css("flex-basis", "calc(100% - 8px)");
            $('#css-code').css("flex-basis", 0);
            $('#html-code').css("flex-basis", 0);
        }
    },

    collapseBlock: function(el){
        if (el === 'html') {
            $('#html-code').css("flex-basis", 0);
            $('#js-code').css("flex-basis", "calc(50% - 8px)");
            $('#css-code').css("flex-basis", "calc(50% - 8px)");
        }
        if (el === 'css') {
            $('#css-code').css("flex-basis", 0);
            $('#js-code').css("flex-basis", "calc(50% - 8px)");
            $('#html-code').css("flex-basis", "calc(50% - 8px)");
        }
        if (el === 'js') {
            $('#js-code').css("flex-basis", 0);
            $('#css-code').css("flex-basis", "calc(50% - 8px)");
            $('#html-code').css("flex-basis", "calc(50% - 8px)");
        }
    },

    openSettingsTab: function(tab, from){
        console.log(from);
        $("#settings-tabs").data('tabs').open(tab);
        Metro.dialog.open("#code-settings");
    },

    layout: function (layout, hash) {
        Sandbox.sendData({
            layout: layout,
            hash: hash
        }, "/code/layout", null, function(){
            Sandbox.reload();
        });
    }
};

Sandbox.init();

