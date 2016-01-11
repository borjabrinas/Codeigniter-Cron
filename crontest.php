#!/usr/bin/php
<?php

	/*

	//Add to crontroller cron class.

	if(! $this->input->is_cli_request() || !defined('CRON'))
	exit('No direct script url allowed');

	//Example cron function default.

	function testcron($valor= '1', $valor2 = '2')
	{	
		echo("Test ". $valor . $valor2);
	}

	*/
	
	$config['SERVER_NAME']         = 'localhost'; //The name of the server 
	$config['CRON_CI_INDEX']	   = ''; // Your CodeIgniter main index.php file
	$config['MAX_PARAMETERS']      = 5; //Limit number of parameters via cli
	$config['LOG_DEFAULT']         = ''; //Default directory

	define('CRON', TRUE); // Block non-CLI calls
	define('CRON_CI_INDEX', $config['CRON_CI_INDEX']);

	$script = array_shift($argv);
	$cmdline = implode(' ', $argv);

	$_SERVER['argv']=array();
	
	$usage = "Usage: cron.php -controller=controller/method -parameter1=p1 -parameter2=p2 .... -screen=1 -log=logs\cronlog.log -timeout=100\n\n";

	foreach($argv as $arg)
	{
		$valid=FALSE;
		$i=1;
		list($param, $value) = explode('=', $arg);

    	switch($param)
	    {
	        case '-controller':
	            $_SERVER['PATH_INFO'] = $value; //Var created by webserver
	            $_SERVER['REQUEST_URI'] = $value;
	            $_SERVER['SERVER_NAME'] = $config['SERVER_NAME'];
	            $_SERVER['REMOTE_ADDR'] = $config['SERVER_NAME'];
	            array_push($_SERVER['argv'],$config['SERVER_NAME']);
				array_push($_SERVER['argv'],$value);

	        break;
	        case '-screen':
	        	if($value=='1') $value=TRUE;
	            define('CRON_FLUSH_BUFFERS', $value);
	        break;
	        case '-log':
	            define('CRON_LOG', $value);
	        break;
	        case '-timeout':
	            define('CRON_TIME_LIMIT', $value);
	        break;
	        default:
	        	for($i;$i<=$config['MAX_PARAMETERS'];$i++)
			    {
			    	if($param=='-parameter'.$i)
			    	{
			    		array_push($_SERVER['argv'],$value);
			    		$valid=TRUE;
			    	}
			    }
			    if($valid==FALSE) {die($usage);}
	        break;
	    }
	}

	//Default values

	if(!defined('CRON_FLUSH_BUFFERS')){define('CRON_FLUSH_BUFFERS', FALSE);}
	if(!defined('CRON_LOG')){define('CRON_LOG', $config['LOG_DEFAULT']);}
	if(!defined('CRON_TIME_LIMIT')){define('CRON_TIME_LIMIT', 0);}


	set_time_limit(CRON_TIME_LIMIT);
	ob_start();
	chdir(dirname( CRON_CI_INDEX));
	require(CRON_CI_INDEX);											
	$output = ob_get_contents();
	
	if(CRON_FLUSH_BUFFERS === TRUE)
		while(@ob_end_flush());										
	else
		ob_end_clean();

	error_log("////// ".date('Y-m-d H:i:s')." cron $cmdline\r\n", 3, CRON_LOG);
	error_log(str_replace("\n", "\r\n", $output), 3, CRON_LOG);
	error_log("\r\n////// \r\n\r\n", 3, CRON_LOG);
	echo "\n\n"; 

?>
