<!DOCTYPE html>
<html>
<head>
	<title>글 쓰기</title>
</head>

<body>
	<form name="frm" action="write_process.php" method="POST" enctype="multipart/form-data">
		<h1>글 쓰기</h1>
		<p>이름: <input type="text" name="nickName" autocomplete="off" /></p>
		<p>제목: <input type="text" name="title" autocomplete="off" /></p>
		<p>비밀번호: <input type="password" name="pw" /></p>
		내용:
		<p><textarea name="content"></textarea></p>
		<button type="submit">작성</button>
		<a href="index.php">목록</a>
		<h3>파일 첨부하기</h3>
		<p><input type="file" name="fileUp" /></p>
	</form>
	<!--하나의 html 파일에서 body 안에 여러개의 form 태그를 사용 할 수 있다.
		input type="file" 은 파일 첨부를 할 수 있게 파일 선택 창을 띄워 준다.
		파일을 첨부할때 이제 파일의 확장자를 확인 할 차례이다.
		naem 은 write_process.php 로 보냈을때 write_process 에서 받을 이름이다.


	-->
</body>
</html>