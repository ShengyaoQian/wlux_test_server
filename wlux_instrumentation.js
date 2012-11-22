// This is the main WebLabUX test site instrumentation & data reporting
// code. Anything that needs to be done when the page is loaded should
// be added to the start() function in our WLUX module (which is called
// by the jQuery ready() function at the bottom of this file).

// The code below assumes the first page is passed a query string
// parameter containing the session id, which it uses to query the
// server for additional info, and then passes along to subsequent pages.


// avoid conflicting with jQuery on the study site (rename $ to $wlux)
$wlux = jQuery.noConflict();

// We're using the Javascript module pattern to avoid polluting the
// global namespace. The only object we'll export is called WLUX, which
// will be used to access any functions or properties we wish to make
// publicly available.
var WLUX = (function() {

    var sessIdKey = "wlux_session";
    var condIdKey = "wlux_condition";

    // are we testing locally?
    var host = window.location.host;
    var DEBUG = (host.indexOf("localhost") != -1) ||
                (host.indexOf("127.0.0.1") != -1);

    var loggerURL = "http://staff.washington.edu/rbwatson/logger.php";
    var studyDataURL = "http://staff.washington.edu/rbwatson/study_data.php";
    if (DEBUG) {
        loggerURL = "/server/logger.php";
        studyDataURL = "/server/study_data.php";
    }

    // gets a querystring parameter using its key
    // http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values
    function getQsParam(key) {
        var match = RegExp('[?&]' + key + '=([^&]*)')
                        .exec(window.location.search);
        return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
    }

    // appends the session id and condition id to all links on a page
    function updateLinks(sessionId, conditionId) {
        $wlux("a").attr('href', function(i, val) {
            if (typeof(val) == 'undefined')
                return; // element has no href, no change

            // check whether it contains a fragment. Fragments must
            // be moved to the end of the url to function properly
            frag = '';
            var ndx = val.indexOf('#');
            if (ndx != -1) {
                if (ndx === 0) // contains only a fragment, don't modify
                    return val;

                // extract the fragment and we'll append it later
                val = val.substr(0, ndx);
                frag = val.substr(ndx);
            }

            // are we starting a new query string or appending to one?
            var start = (val.indexOf('?') == -1) ? '?' : '&';
            var strArray = [val, start, sessIdKey, '=', sessId,
                            '&', condIdKey, '=', condId, frag];

            strArray = strArray.join('');
        });
    }

    // logs page transitions
    function logTransition(from, to) {
        $wlux.post(loggerURL, {"type" : "transition",
                               "wlux_session" : SESSION_ID,
                               "from" : from,
                               "to" : to});
    }

    function loadCSS(cssURL) {

    }

    // This function will be called on dom ready.
    function start() {
        var sessionId = getQsParam(sessIdKey);
        if (sessionId === null)
            return;

        // disableClicks();

        // var data = getStudyData();
        // we'll just use this for now until the service is setup
        data = {cssURL: "css/test1.css",
                returnURL: "/server/end.php",
                buttonText: "End Study",
                conditionId: 1};

        loadCSS(data.css);
        setupReturnButton(data.buttonText, data.returnURL);
        updateLinks(sessionId, conditionId);

        // log the page open event immediately
        $wlux.post(loggerURL, {"type" : "open",
                               "wlux_session" : SESSION_ID,
                               "location" : window.location.href});

        // Log transitions
        // Note: this only handles page transitions caused by clicking
        // anchor tags.
        $wlux("a").click(function(e) {
            var from = window.location.href;
            var to = e.target.href;
            logTransition(from, to);
        });

        // enableClicks();
    }

    // Here we add the functions and variables we wish to export to
    // the WLUX object
    var exports = {};
    exports.start = start;

    return exports;
})(); // module pattern - we've created an anonymous function and immediately call it


$wlux(document).ready(function() {
    WLUX.start();
});
