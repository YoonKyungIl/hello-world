<?php
	function dbconn(){
		$host_name="localhost";
		$db_user_id="root";
		$db_name="kyungil";
		$db_pw="1234";
		$connect=(function_exists("mysql_connect")) ? @mysql_connect($host_name, $db_user_id, $db_pw, $db_name) : @mysqli_connect($host_name, $db_user_id, $db_pw, $db_name);
		//mysql_query("set names utf8", $connect);
		mysqli_select_db($connect, $db_name);

			if(!$connect)die("연결에 실패".mysql_error());
			return $connect;
}

//에러메세지 출력
	function Error($msg)
	{
		  echo "
		  <script>
		  window.alert('$msg');
		  history.back(1);
		  </script>
		  ";
		  exit(); //위 에러 메세지만 띄우기
	}

	function debug($array) {
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
?>