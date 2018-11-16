<?php

//echo 'pull4';

//echo shell_exec("/var/www/html/pbox14/pull/pull.sh");

//sudo /bin/bash /var/www/my_bash_script.sh

//$output = shell_exec("/var/www/html/pbox14/pull/pull.sh");
//echo "<pre>$output</pre>";
//
//$output = shell_exec("cd /var/www/html/pbox14 && ls -la && sudo git pull");
//echo "<pre>$output</pre>";


echo shell_exec("cd /var/www/html/pbox14/ && git pull 2>&1")
//echo shell_exec("bash pull.sh 2>&1")

//$output = shell_exec("ls -la /efs");
//echo "<pre>$output</pre>";

?>