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
    function updateLinks(sessId, condId) {
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

            return strArray.join('');
        });
    }

    // logs page transitions
    function logTransition(from, to, sessionId) {
        $wlux.post(loggerURL, {"type" : "transition",
                               "wlux_session" : sessionId,
                               "from" : from,
                               "to" : to});
    }

    // loads styles from the css file at the given url
    function loadCSS(cssURL) {
        // IE requires us to call this ie-only function
        if (document.createStyleSheet) {
            document.createStyleSheet(cssURL);
        } else {
            var css = $wlux('<link>').attr({'rel': 'stylesheet',
                                            'type': 'text/css',
                                            'href': cssURL});
            $wlux('head').append(css);
        }
    }

    //adds a fixed-position "end" button to page
    function setupReturnButton(text, url) {
        var button = $wlux('<button>').attr({'type': 'button'})
                                      .css({'position': 'relative',
                                            'width': '80px',
                                            'margin-left': '-40px',
                                            'margin-top': '5px',
                                            'left': '50%'})
                                      .text(text);
        var link = $wlux('<a>').attr({'href': url});
        var div = $wlux('<div>').attr({'id': 'endButton'})
                                .css({'position': 'fixed',
                                      'background-color': 'red',
                                      'right': '10px',
                                      'top': '10px',
                                      'width': '100px',
                                      'height': '30px'});
        link.append(button);
        div.append(link);
        $wlux('body').append(div);
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
                returnURL: "http://staff.washington.edu/rbwatson/end.html",
                buttonText: "End Study",
                conditionId: 1};

        loadCSS(data.cssURL);
        setupReturnButton(data.buttonText, data.returnURL);
        updateLinks(sessionId, data.conditionId);

        // log the page open event immediately
        $wlux.post(loggerURL, {"type" : "open",
                               "wlux_session" : sessionId,
                               "location" : window.location.href});

        // Log transitions
        // Note: this only handles page transitions caused by clicking
        // anchor tags.
        $wlux("a").click(function(e) {
            var from = window.location.href;
            var to = e.target.href;
            logTransition(from, to, sessionId);
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
