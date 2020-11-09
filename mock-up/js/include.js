function include(filename, afterfunc) {

    include.seq = (include.seq) ? include.seq + 1 : 1;

    var id = new Date().getTime() + "-" + include.seq;
    var inc = document.createElement("iframe");

    inc.id = "inc-" + id;
    inc.src = filename;
    inc.style.display = "none";
    document.write("<span id=\"" + id + "\"></span>");

    var incfunc = function () {

        var s = (function () {
            var suffix = (n = filename.lastIndexOf(".")) >= 0 ? filename.substring(n) : "default";
            if (suffix == ".html") return inc.contentWindow.document.body.innerHTML;
            if (suffix == ".txt") return inc.contentWindow.document.body.firstChild.innerHTML;
            if (suffix == "default") return inc.contentWindow.document.body.innerHTML;
        })();

        var span = document.getElementById(id);

        var insertBeforeHTML = function (htmlStr, refNode) {
            if (document.createRange) {
                var range = document.createRange();
                range.setStartBefore(refNode);
                refNode.parentNode.insertBefore(range.createContextualFragment(htmlStr), refNode);
            } else {
                refNode.insertAdjacentHTML('BeforeBegin', htmlStr);
            }
        };

        insertBeforeHTML(s.split("&gt;").join(">").split("&lt;").join("<"), span);
        document.body.removeChild(inc);
        span.parentNode.removeChild(span);
        if (afterfunc) afterfunc();
    };

    if (window.attachEvent) {
        window.attachEvent('onload',
            function () {
                document.body.appendChild(inc);
                inc.onreadystatechange = function () {
                    if (this.readyState == "complete") incfunc();
                };
            });
    } else {
        document.body.appendChild(inc);
        inc.onload = incfunc;
    }
}
