<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function smtp_mail($to, $from, $message, $user, $pass, $host, $port)
{
	if ($h = fsockopen($host, $port))
	{
		$data = array(
			0,
			"EHLO $host",
			'AUTH LOGIN',
			base64_encode($user),
			base64_encode($pass),
			"MAIL FROM: <$from>",
			"RCPT TO: <$to>",
			'DATA',
			$message
		);
		foreach($data as $c)
		{
			$c && fwrite($h, "$c\r\n");
			while(substr(fgets($h, 256), 3, 1) != ' '){}
		}
		fwrite($h, "QUIT\r\n");
		return fclose($h);
	}
}

$to='{{to who}}';
$host     = "{{SMTP Host}}";
$port = {{port}};
$from   = "{{from who}}";
$user       = "{{from who}}";
$pass       = "{{password}}";
$template = "Subject: =?UTF-8?B?VGVzdCBFbWFpbA==?=\r\n"
."To: <".$to.">\r\n"
."From: ".$from."\r\n"
."MIME-Version: 1.0\r\n"
."Content-Type: text/html; charset=utf-8\r\n"
."Content-Transfer-Encoding: base64\r\n\r\n"
."PGgxPlRlc3QgRW1haWw8L2gxPjxwPkhlbGxvIFRoZXJlITwvcD4=\r\n.";


if(smtp_mail($to, $from, $template, $user, $pass, $host, $port)){
	echo "Mail sent\n\n";
}else{
	echo "Some error occured\n\n";
}
