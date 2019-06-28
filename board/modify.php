<html>
<head>
	<title>글 수정</title>
</head>

	<?php
		include "DbConnect.php";  //데이터 베이스를 연결하는 php 파일을 include 하여 사용했다.
		$conn=dbconn(); //dbconn이란 함수를 호출하면 데이터 베이스 연결과 함께 내가 사용 할 테이블을 선택 하게 된다.
						//원래는 이렇게 함수 호출로 테이블을 하나만 선택 해서는 안된다. 왜냐하면 테이블이 여러개 일 경우에는 각각 따로 선택해야되기 때문이다.
				//var_dump($_GET);

		$number=$_GET["no"];

		$query="
			SELECT
				*
			FROM
				user
			WHERE idx='".$number."'
			";

		$result=mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);

	?>
	<body>
	<h1>글 수정</h1>

	    <form name="frm" action="modify_process.php?no=<?=$row["idx"]?>" method="POST" enctype="multipart/form-data">
		<p>이름: <input type="text" name="nickName" value="<?=$row["name"]?>" /></p> <!-- value 뒤에 쌍따옴표 빼먹지 말것.-->
		<p>제목: <input type="text" name="title" value="<?=$row["title"]?>" /></p>
		<p>비밀번호: <input type="password" name="pw" /></p>
		내용:
		<p><textarea name="content"><?=$row["content"]?></textarea></p>
		<button type="submit">수정</button>
		<a href="index.php">목록</a>
		<h3>파일 첨부하기</h3>
		<p><input type="file" name="fileModify" /> 기존 파일:
			<?
				$tmpName=basename($row["path"]);
				echo $tmpName;
			?>
		</p>
	</form>

	</body>
	<?php
		mysqli_close($conn);
	?>
</html>