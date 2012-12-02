#! /bin/bash
# A simple script for minifying our javascript on Posix-compliant machines.
java -jar compiler.jar --js jquery.js --js wlux_instrumentation.js --js_output_file wlux_instrumentation.min.js


