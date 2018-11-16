<?php
session_start();

if(!isset($_SESSION['served']) || empty($_SESSION['served'])) {
	$_SESSION['served'] = 0;
}

if(!isset($_SESSION['time']) || empty($_SESSION['time'])) {
	$_SESSION['time'] = time();
}

if(!isset($_SESSION['isPulling']) || empty($_SESSION['isPulling'])) {
	$_SESSION['isPulling'] = 0;
}

if(!isset($_SESSION['pulledTime']) || empty($_SESSION['pulledTime'])) {
	$_SESSION['pulledTime'] = 0;
}

$MAX_USE = 20; //Can use without auth
$MAX_ACCESS_TIME_DIFF = 30; //after this seconds need auth
$MAX_PULL_TIMEOUT = 10; //waiting time for next pull request

$user = "admin";
$pass = "admin";

$_SESSION['served'] += 1;

$differenceInSeconds = time() - $_SESSION['time'];

if(isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] == $user && isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW'] == $pass) {
	if($_SESSION['served'] > $MAX_USE || $differenceInSeconds > $MAX_ACCESS_TIME_DIFF){
		$_SESSION['served'] = 0;
		unset($_SESSION['time']);


		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');

		echo "<pre>Unauthorized Access.</pre>";
	}else{
		$differenceInSecondsPull = 0;

		if(isset($_SESSION['pulledTime']) && !empty($_SESSION['pulledTime'])){
			$differenceInSecondsPull = time() - $_SESSION['pulledTime'];
		}

		if($_SESSION['isPulling'] == 0){
			$_SESSION['isPulling'] = 1;

			$_SESSION['pulledTime'] = time();


			echo "<pre>-------------------------<br/>Executing Pull Request...<br/>-------------------------</pre>";

			$output = shell_exec("cd /var/www/html/pbox14/ && git pull 2>&1");
			echo "<pre>".$output."</pre>";

			sleep(1);

			echo "<pre>-------------------------<br/>Pulling completed...<br/>-------------------------</pre>";
		}else {
			if ($differenceInSecondsPull > $MAX_PULL_TIMEOUT) {
				$_SESSION['isPulling'] = 0;
				$_SESSION['pulledTime'] = 0;
			}

			$wait = $MAX_PULL_TIMEOUT - $differenceInSecondsPull;
			$wait = ($wait <= 0) ? 1 : $wait;

			echo "<pre>Cannot pull data, Please wait " . $wait . " seconds</pre>";
		}
	}
} else {
	header('WWW-Authenticate: Basic realm="My Realm"');
	header('HTTP/1.0 401 Unauthorized');

	echo "<pre>Unauthorized Access.</pre>";
}

?>

<style>
	*{
		margin-top: 50px;
		background: #1b6b93;
	}

	pre{
		text-align: center;
		width: 100%;
		font-size: 30px;
		color: #ffffff;
		font-weight: bold;
	}
</style>
