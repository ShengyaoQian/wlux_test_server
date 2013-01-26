##Server status
The http://staff.washington.edu/rbwatson server has been updated to this repo as  of 1/26/2013 @ 14:39.

The wlux_test_server code runs on http://staff.washington.edu/rbwatson and is used to test server-side code while we're experimenting with WebLabUX utilities and "plumbing." I'll make sure that what is in the master repo is also on the server.

To run the demo, go to http://staff.washington.edu/rbwatson/start.php 

##Release notes
**THIS BUILD IS NOT READY FOR RELEASE -- IT IS FOR TESTING/DEMO ONLY **
When ready for production, the javascript needs to be compiled / minified so that it
will download and run faster on client sites. This can be done using the google closure 
compiler (compiler.jar), via the following command:

   java -jar compiler.jar --js jquery.js --js wlux_instrumentation.js --js_output_file wlux_instrumentation.min.js

This also combines jquery and wlux_instrumentation into a single file. Now test sites need 
only include a single script, `wlux_instrumentation.min.js`.

To avoid having to copy/paste or memorize this command, there are two scripts `compile.sh` and
`compile.bat` which will run the minification command on linux and windows, respectively.

