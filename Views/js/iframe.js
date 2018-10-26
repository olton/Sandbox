function console_out(messages, console_element, clear){
    var i;
    var console_print = function(output, val){
        output.innerHTML += '<div class="log-item"><span class="text-small text-muted">['+((new Date()).format('%H:%M:%S'))+']</span> ' + val + '</div>';
    };

    if (!!clear) {
        console_element.innerHTML = "";
    }

    if (typeof messages === "string") {
        messages = [messages];
    }

    for(i = 0; i < messages.length; i++) {
        var m = messages[i], r = "";

        if (m === undefined || m === 'undefined') {
            console_print(console_element, "undefined");
            continue;
        }
        if (m === null || m === 'null') {
            console_print(console_element, "null");
            continue;
        }

        if (m instanceof Error) {
            r = "<span class='fg-red'>Error:</span> "+m.message;
        } else {
            if (m instanceof String || m instanceof Number) {
                r = m.toString();
            } else {
                if (typeof m === 'object') {
                    $.each(m, function(key, val){
                        console_print(console_element, "<span class='text-bold'>"+key+"</span>" + ":" + " " + (Metro.utils.isValue(val) ? val.toString() : ""));
                    });
                } else if (!isNaN(m)) {
                    r = String(m);
                } else {
                    r = JSON.stringify(m);
                }
            }
        }

        console_print(console_element, r);
    }
}

function receiveMessage(e){
    var console_element = document.getElementById("console-output");

    if (!Array.isArray(e.data)) {
        return ;
    }

    console_out(e.data, console_element, true);
}

function iframeErrorHandler(message, url, row, col, error){
    console_out(message + " on " + url + " " + row+":"+col, document.getElementById("console-output"), true);
}

Metro.utils.iframeBubbleMouseMove(document.getElementById("iframe"));
window.addEventListener("message", receiveMessage, false);



