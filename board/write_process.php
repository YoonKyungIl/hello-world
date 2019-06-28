<?php
	include "DbConnect.php";
	$conn=dbconn();
	$userName=$_POST["nickName"];
	$userTitle=$_POST["title"];
	$userPw=$_POST["pw"];
	$userContent=$_POST["content"];
	$userDate=date("Ymd");


	 // 업로드된 이미지를 저장하기 위한 폴더 지정.
	$targetFileName=basename($_FILES["fileUp"]["name"]); //wallpaper.jpg
	$tmpName=basename($_FILES["fileUp"]["tmp_name"]);
	$targetDir="/STUDY/board/uploads/"; //절대 경로이다.
	//basename은 경로의 마지막 이름을 가져오는 함수.
	//ex) D:\Autoset\AutoSet10\public_html\board\uploads\wallpaper.jpeg 면 wallpaper.jpeg만 가지고 온다.
	//move_uploaded_file (파일 이름 , 목적지 경로 );
	//$_FILES["파일이름"]["ERROR"] = 4 라면 첨부파일이 없는것.
	//없을때는 경로를 저장하면 안됨.
	$tmpPath=$_FILES["fileUp"]["tmp_name"];
	move_uploaded_file($tmpPath, $_SERVER["DOCUMENT_ROOT"].$targetDir.$targetFileName);
	$userPath=$targetDir.$targetFileName;
	/*debug(
				array("FILES" => $_FILES, "targetDir" => $targetDir, "targetFileName" => $targetFileName, "userPath"=>$userPath)
		 );
		exit();*/

	//쌍따옴표 주의 시작과 끝을 잘 확인해야함
	if($_FILES["fileUp"]["error"]==0)
	{
		mysqli_query($conn, "
		INSERT INTO
		 user
		  (name, title, pw, content, date, path)
		 VALUES
		  ('".$userName."', '".$userTitle."', '".$userPw."', '".$userContent."', '".$userDate."', '".$userPath."');");
	// . (연결자를 통해서 변수를 구별한다.)

	mysqli_close($conn);
	header("location: /STUDY/board/index.php");
	}
	else
	{
		mysqli_query($conn, "
		INSERT INTO
		 user
		  (name, title, pw, content, date)
		 VALUES
		  ('".$userName."', '".$userTitle."', '".$userPw."', '".$userContent."', '".$userDate."');");
	// . (연결자를 통해서 변수를 구별한다.)

	mysqli_close($conn);
	header("location: /STUDY/board/index.php");
	}


?>