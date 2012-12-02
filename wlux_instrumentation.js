// This is the main WebLabUX test site instrumentation & data reporting
// code. Anything that needs to be done when the page is loaded should
// be added to the start() function in our WLUX module (which is called
// by the jQuery ready() function at the bottom of this file).

// The code below assumes the first page is passed a query string
// parameter containing the session id, which it uses to query the
// server for additional info, and then passes along to subsequent pages.
// TODO: upon opening, the page checks for a wlux_session query string
// parameter. If found, it makes it a cookie and immediately redirects
// the page so the param is no longer part of the url. If not found, it
// checks for the cookie.

// avoid conflicting with jQuery on the study site (rename $ to $wlux)
$wlux = jQuery.noConflict();

// We're using the Javascript module pattern to avoid polluting the
// global namespace. The only object we'll export is called WLUX, which
// will be used to access any functions or properties we wish to make
// publicly available.
var WLUX = (function() {

    /***************************************************************
     *             Variables global to the module                  *
     **************************************************************/

    var sessIdKey = "wlux_session";
    var condIdKey = "wlux_condition";
    var SESSION_ID = null;
    var done = false; // are we done loading wlux?
    var study_data = {};

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

    /***************************************************************
     *                   Function Definitions                      *
     **************************************************************/

    // gets a querystring parameter using its key
    // http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values
    function getQsParam(key) {
        var match = RegExp('[?&]' + key + '=([^&]*)')
                        .exec(window.location.search);
        return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
    }

    // appends the session id and condition id to all links on a page
    function updateLinks() {
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
            var strArray = [val, start, sessIdKey, '=', SESSION_ID, frag];

            return strArray.join('');
        });
    }

    // logs page transitions.
    function logTransition(from, to, a_id, a_class) {
        // $.post is asynchronous by default, which causes problems if the
        // browser decides to follow the link before carrying out our request
        $wlux.ajaxSetup({async: false}); //
        // if undefined, use ""
        a_id = a_id || "";
        a_class = a_class || "";
        $wlux.post(loggerURL, {"data" : {"type": "transition",
                                         "wlux_session": SESSION_ID,
                                         "from": from,
                                         "to": to,
                                         "a_class": a_class,
                                         "a_id": a_id}
                              }
                  );
    }

    // logs page openings
    function logOpen() {
        $wlux.ajaxSetup({async: false}); // do this immediately
        $wlux.post(loggerURL, {"data" : {"type": "open",
                                         "wlux_session": SESSION_ID,
                                         "location": window.location.href}
                              }
                  );
    }

    // loads styles from the css file at the url supplied to
    // us with the study data
    function loadCSS() {
        // IE requires us to call this ie-only function
        if (document.createStyleSheet) {
            document.createStyleSheet(study_data.cssURL);
        } else {
            var css = $wlux('<link>').attr({'rel': 'stylesheet',
                                            'type': 'text/css',
                                            'href': study_data.cssURL});
            $wlux('head').append(css);
        }
    }


    // Adds a hideable toolbar to the top of the page, which includes task text
    // and an 'End Study' button that sends the user to the return url.
    function setupToolbar() {
        var toolbarHeight = '50px';
        var button = $wlux('<button>').css({'width': '100px'})
                                      .text(study_data.buttonText);
        var link = $wlux('<a>').attr({'href': study_data.returnURL})
                               .css({'float': 'right'});
        var toolbar = $wlux('<div>').attr({'id': 'wlux_toolbar'})
                                    .css({'position': 'fixed',
                                          'top': '0px',
                                          'left': '0px',
                                          'background-color': 'black',
                                          'width': '100%',
                                          'height': toolbarHeight});
        var toggle = $wlux('<div>').attr({'id': 'wlux_toggle'})
                                   .css({'position': 'fixed',
                                         'top': toolbarHeight,
                                         'left': '20px',
                                         'color': 'white',
                                         'font-size': '.8em',
                                         'width': '50px',
                                         'text-align': 'center',
                                         'cursor': 'hand',
                                         'cursor': 'pointer',
                                         'padding': '.2em',
                                         'background-color': 'black',
                                         'border-top': '1px dashed white',
                                         'border-radius': '0 0 .3em .3em'})
                                   .text('hide');
        var taskText = $wlux('<p>').text('Task: ' + study_data.taskText)
                                   .css({'float': 'left',
                                         'color': 'white',
                                         'margin': '0'});

        // animate the toolbar
        toggle.click(function() {
            var hidden = toolbar.hasClass('hidden');
            if (hidden) { // show the toolbar
                toolbar.animate({top: '0px'});
                toggle.animate({top: toolbarHeight});
                toggle.text('hide');
                toolbar.removeClass('hidden');
            } else { // hide the toolbar
                toolbar.animate({top: '-' + toolbarHeight});
                toggle.animate({top: '0px'});
                toggle.text('show');
                toolbar.addClass('hidden');
            }
        });

        // layout the toolbar
        link.append(button);
        toolbar.append(taskText);
        toolbar.append(link);
        $wlux('body').append(toolbar);
        $wlux('body').append(toggle);

        // adds some margin to the direct children of the toolbar
        $wlux("#wlux_toolbar > *").css("margin",".3em");

        // automatically hide the toolbar after 2 seconds, so the user doesn't need to
        // do it manually on every page load
        setTimeout(function() { toggle.trigger('click'); }, 2000);
    }

    // Currently only handles page transitions caused by clicking anchor tags.
    function addClickHandlers() {
        // Log transitions via anchor tags.
        $wlux("a").click(function(e) {
            var from = window.location.href;
            // currentTarget is necessary for clicking of buttons wrapped in an a tag
            var to = e.target.href || e.currentTarget.href;
            var a_id = $wlux(this).attr('id');
            var a_class = $wlux(this).attr('class');
            logTransition(from, to, a_id, a_class);
        });
    }

    /***************************************************************
     *                 Functions Called Externally                 *
     **************************************************************/

    // This function will be called immediately, when this script is
    // being parsed (i.e. crucially, before the body loads).
    // Instead of disabling, enabling clicks (which would require
    // waiting for the page to load), we just hide the body as soon
    // as it loads, and show it again later, in the start function
    var interval;
    function preLoad() {
        // try to find/hide body as the page loads, to keep users from interacting
        // with the page until all of our instrumentation is setup
        interval = setInterval(function() {
            var body = $wlux('body');
            if (done) { // set in ajax success callback after study data is loaded
                // the page loaded really fast - even before this method was called
                // clear the timeout and don't hide the body, we're DONE
                clearInterval(interval);
                return;
            }
            if (body.length > 0) {
                body.css({'visibility': 'hidden'});
                clearInterval(interval);
            }
        }, 50); // try every 50ms
    }

    // This function will be called on dom ready.
    // SESSION_ID and study_data should have already been set in preLoad()
    function start() {
        SESSION_ID = getQsParam(sessIdKey);
        if (SESSION_ID === null)
            return;

        // request the study data from the server asynchronously
        // (via JSONP to avoid the browser's same origin policy) while we're
        // waiting for the page to load
        $wlux.ajax({
            url: studyDataURL,
            type: "GET",
            dataType: "jsonp",
            data: {"wlux_session": SESSION_ID},
            success: function(data) {
                study_data = data; // just store it locally for later use

                logOpen();
                loadCSS();
                setupToolbar();
                updateLinks();
                addClickHandlers();

                // everything is loaded and setup. show the body
                $wlux('body').css({'visibility': 'visible'});
                done = true;
            },
            error: function() {
                // error getting study data, ensure body is visible and return
                $wlux('body').css({'visibility': 'visible'});
                done = true;
                return;
            },
            cache: true // cache the results, in case subsequent pages make the same request
        });
    }

    // Module pattern - functions and variables added to exports object
    // will become parte of the WLUX object, everything else is private
    var exports = {};
    exports.preLoad = preLoad;
    exports.start = start;

    return exports;
})(); // module pattern - we've created an anonymous function and immediately call it

// use for initial set up, currently just hides the body
WLUX.preLoad();

// do wlux stuff as soon as the dom is ready
$wlux(document).ready(function() {
    WLUX.start();
});
