# Codeigniter-Cron
Codeigniter Cron

You can access all CodeIgniter controller methods via the command line.  Let’s start with a simple code


Add to crontroller cron class.
-----

```php
  if(! $this->input->is_cli_request() || !defined('CRON'))
  exit('No direct script url allowed');
```  

Example Cron Class
-----

```php
	function testcron($valor= '1', $valor2 = '2')
	{	
		echo("Test ". $valor . $valor2);
	}
```

Usage Cron Call
-----

```shell
Usage: cron.php -controller=controller/method -parameter1=p1 -parameter2=p2 .... -screen=1 -log=logs\cronlog.log -timeout=100\n\n
``` 
