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
            parent.postMessage(Array.prototype.slice.call(arguments, 0), "*");
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
    (function(){

        {{_js_code_}}

    })();
</script>
</body>
</html>
CODE_TEMPLATE;

