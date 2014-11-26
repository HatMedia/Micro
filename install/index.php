<?php

// build config file


// duplicate src folder
file_get_contents('assets/clean_install.sql');
shell_exec('cp src/* ... target ..');

// put everyhting together ;).