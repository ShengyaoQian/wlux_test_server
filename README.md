The wlux_test_server code runs on http://staff.washington.edu/rbwatson and is used to test server-side code while we're experimenting with WebLabUX utilities and "plumbing." I'll make sure that what is in the master repo is also on the server.

# Development
For testing purposes, you need to clone both wlux_test_site and
wlux_test_server into directories called `site` and `server`, respectively,
in order for everything to work properly.

Then, start up your webserver and navigate to `localhost/server/start.php` to
begin a study. Study results will be located at `localhost/server/log.txt`.

Also, don't forget to make sure the server folder has write privileges.
On linux, it's as simple as changing to the site root and typing
`sudo chmod 664 server`. You may also need to modify the permissions of the
text files inside of `server` so that you can write to them.

