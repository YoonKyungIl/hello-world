<html>
<body>
	<?php

		include "DbConnect.php";  //데이터 베이스를 연결하는 php 파일을 include 하여 사용했다.
		$conn=dbconn(); //dbconn이란 함수를 호출하면 데이터 베이스 연결과 함께 내가 사용 할 테이블을 선택 하게 된다.
						//원래는 이렇게 함수 호출로 테이블을 하나만 선택 해서는 안된다. 왜냐하면 테이블이 여러개 일 경우에는 각각 따로 선택해야되기 때문이다.
				//var_dump($_GET);

		$number=$_GET["reNo"];

		$query="
			SELECT
				*
			FROM
				user_reply
			WHERE
				idx=$number
			";

		$result=mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
	?>
	<h1>댓글 수정</h1>
	    <form name="frm" action="reply_modify_process.php?reNo=<?=$row["idx"]?>" method="POST">
		<p>이름: <input type="text" name="reName" value="<?=$row["re_name"]?>" /></p> <!-- value 뒤에 쌍따옴표 빼먹지 말것.-->
		내용:
		<p><textarea name="reContent"><?=$row["re_content"]?></textarea></p>
		<p>비밀번호: <input type="password" name="rePw" /></p>
		<button type="submit">수정</button>
		<a href="index.php">목록</a>

</body>
</html>