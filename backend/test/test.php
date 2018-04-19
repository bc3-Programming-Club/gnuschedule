#!/usr/bin/env php
<?php

$files = scandir('./');

if ($files !== FALSE) {
    foreach ($files as $file) {
        if (strpos($file, '.test.php') !== FALSE) {
            print "Running tests for $file\n";

            $output = NULL;
            $exit_code = NULL;
            exec("php $file", $output, $exit_code);

            $stderr = $output[0];
            $stdout = $output[1];

            print "$stderr\n";
            print "$stdout\n";
        }
    }
}
