The wlux_test_server code runs on http://staff.washington.edu/rbwatson and is used to test server-side code while we're experimenting with WebLabUX utilities and "plumbing." I'll make sure that what is in the master repo is also on the server.

The files that are on the server are currently:

ping.php:
the server-side file that returns a json object. Eventually, something like this could be used to communicate between the WebLabUX server and the website under test. At the moment, it returns a single key/value pair. (imaginatively named "key", returning "value") It accepts a query string, "jsonp" which is used to provide the jsonp "padding" for the data object.

You can see what this looks like by opening this link: http://staff.washington.edu/rbwatson/ping.php?jsonp=myMethod

phpinfo.php:
Displays the PHP configuration of the server.
