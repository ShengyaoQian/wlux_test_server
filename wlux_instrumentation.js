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

    var SESSION_ID = null;
    var done = false; // are we done loading wlux?

    // set the url dynamically depending on whether we're in the
    // development environment or the production environment
    var host = window.location.host;
    var LOCAL = (host.indexOf("localhost") != -1) ||
                (host.indexOf("127.0.0.1") != -1);

    var loggerURL = "http://staff.washington.edu/rbwatson/logger.php";
    var studyDataURL = "http://staff.washington.edu/rbwatson/study_data.php";

    if (LOCAL) {
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
    function updateLinks(condId) {
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
            var strArray = [val, start, sessIdKey, '=', SESSION_ID,
                            '&', condIdKey, '=', condId, frag];

            return strArray.join('');
        });
    }

    // logs page transitions
    function logTransition(from, to, conditionId) {
        $wlux.post(loggerURL, {"type": "transition",
                               "wlux_session": SESSION_ID,
                               "wlux_condition": conditionId,
                               "from": from,
                               "to": to});
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


    // instead of disabling, enabling clicks (which would require
    // waiting for the page to load), we just hide the body as soon
    // as it loads, and show it again later, in the start function
    var timeout;
    function hideBody() {
        var body = $wlux('body');
        if (done) {
            // finished loading even before this method was called
            // clear the timeout and don't hide the body, we're DONE
            clearTimeout(timeout);
            return;
        }
        if (body.length > 0) {
            body.css({'visibility': 'hidden'});
            clearTimeout(timeout);
        }
    }

    // This function will be called immediately, when this script is
    // being parsed (i.e. crucially, before the body loads)
    function preLoad() {
        timeout = setTimeout(hideBody, 50);
    }

    // asynchronously request the study data from the data service
    function getStudyData() {
        var data = {"wlux_session": SESSION_ID};
        $wlux.post(studyDataURL, data, function(data) {
            loadStudyData(data);
            // todo - check that returned data is valid
        }).error(function() { loadStudyData({}); });
    }

    // calls all the methods that require study data
    function loadStudyData(data) {
        if (data === {}) {
            // error getting study data, just display the body and return
            $wlux('body').css({'visibility': 'visible'});
            done = true;
            return;
        }

        loadCSS(data.cssURL);
        setupReturnButton(data.buttonText, data.returnURL);
        updateLinks(data.conditionId);

        // log the page open event immediately
        $wlux.post(loggerURL, {"type": "open",
                               "wlux_session": SESSION_ID,
                               "wlux_condition": data.conditionId,
                               "location": window.location.href});

        // Log transitions
        // Note: this only handles page transitions caused by clicking
        // anchor tags.
        $wlux("a").click(function(e) {
            var from = window.location.href;
            var to = e.target.href;
            logTransition(from, to, data.conditionId);
        });

        // everything is loaded and setup. show the body
        $wlux('body').css({'visibility': 'visible'});
        done = true;
    }

    // This function will be called on dom ready.
    function start() {
        SESSION_ID = getQsParam(sessIdKey);
        if (SESSION_ID === null)
            return;

        getStudyData();
    }

    // Here we add the functions and variables we wish to export to
    // the WLUX object
    var exports = {};
    exports.preLoad = preLoad;
    exports.start = start;

    return exports;
})(); // module pattern - we've created an anonymous function and immediately call it

// hide the body
WLUX.preLoad();

// do wlux stuff as soon as the dom is ready
$wlux(document).ready(function() {
	// the server_vars object is defined in the script that is
	// was added to the head tag.
	// If data is not defined, then either this page
	// wasn't loaded as part of a study, or there was a problem
	// getting information from the server.
	server_vars = function(data) {
		if (data !== undefined) {
			WLUX.start(data);
		}
	};
});

