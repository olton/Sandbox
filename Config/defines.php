<?php

$CODE_TEMPLATE = <<<CODE_TEMPLATE
<!DOCTYPE html>
<html lang="en" class="{{_html_classes_}}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache" />

    {{_head_}}

    <meta name="keywords" content="{{_tags_}}">
    <meta name="description" content="{{_desc_}}">

    {{_css_links_}}

    <title>{{_title_}}</title>

    <style>
        {{_css_code_}}
    </style>

    <script>
        console.log = function(){
            var i;
            var args = new Array(arguments.length);
            for(i = 0; i < args.length; ++i) {
                args[i] = typeof arguments[i] === 'string' ? arguments[i] : String(arguments[i]) ;
            }            
            parent.postMessage(args, "*");
        };

        window.addEventListener("message", function(e){
            console.log(e.data);
            window.eval(e.data);
        }, false);

        window.onerror = function (message, url, row, col, err) {
            console.log(message);
        };
    </script>
</head>
<body class="{{_body_classes_}}">

{{_html_code_}}

{{_js_links_}}

<script>
    {{_js_code_}}
</script>
</body>
</html>
CODE_TEMPLATE;

$CODE_DEBUG_TEMPLATE = <<<CODE_DEBUG_TEMPLATE
<!DOCTYPE html>
<html lang="en" class="{{_html_classes_}}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache" />

    {{_head_}}

    <meta name="keywords" content="{{_tags_}}">
    <meta name="description" content="{{_desc_}}">

    {{_css_links_}}

    <title>{{_title_}}</title>

    <style>
        {{_css_code_}}
    </style>
</head>
<body class="{{_body_classes_}}">

{{_html_code_}}

{{_js_links_}}

<script>
    {{_js_code_}}
</script>
</body>
</html>
CODE_DEBUG_TEMPLATE;

