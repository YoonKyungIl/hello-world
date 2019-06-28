<!DOCTYPE html>
<html>
<head>
	<title>삭제하기</title>
</head>
<body>
	<h1>삭제 하기</h1>

	<?php
		$num=$_GET["no"]; //게시글 삭제할 게시글 idx
		$type=$_GET["type"]; //댓글 삭제할 댓글 idx

	?>

<!--<form name="frm" action="delete_process.php?no=<?=$num?>&reNo=<?=$renum?>" method="POST"> -->
	<form name="frm" action="delete_process.php" method="POST">
	<p>비밀번호 확인: <input type="password" name="pw" /></p>
	<input type="hidden" name="number" value="<?=$num?>" />
	<input type="hidden" name="type" value="<?=$type?>" />
	<button type="submit"/>확인</button>
	<a href="index.php">목록</a>
	</form>
</body>
</html>