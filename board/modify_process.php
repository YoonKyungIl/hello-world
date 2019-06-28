<?php
	include "DbConnect.php";
	$conn=dbconn();

	$num=$_GET["no"]; //modify.php 에서 idx 를 get 방식으로 받아서 num 변수에 저장. GET으로 보냈기때문에 GET으로 받아야한다.
	$userName=$_POST["nickName"];
	$userTitle=$_POST["title"];
	$userPw=$_POST["pw"];
	$userContent=$_POST["content"];

	//데이터 베이스에서 패스워드를 가져오기
	$query="
		SELECT
			pw, path
		FROM
			user
		WHERE
			idx=$num
	";
	//원래 있던 파일을 삭제하고
	//새로운 경로를 가지고 와서 데이터 베이스에서 새로운 경로를 저장.
	//$_FILE["fileUp"]
	//먼저 데이터 베이스에 있던 데이터를 삭제 해주어야함.

	$result=mysqli_query($conn, $query);
	$row=mysqli_fetch_assoc($result);
	//$oldFilePath=$row["path"];
	$targetDir="/STUDY/board/uploads/";
	$rootDir=$_SERVER["DOCUMENT_ROOT"]; //root 경로를 지정해 주기 위한 변수.

	//새로운 파일 이름
	//새로운 임시 파일 이름
	//새로운 파일 저장 데이터 베이스에 새로운 파일의 경로 추가.
	//비밀번호가 맞고 파일이 있다면 파일 삭제 하고 새로운 경로와 파일 저장.
	//비밀번호가 맞고 파일이 없다면 삭제 안함.
	if($userPw == $row["pw"])
	{

		$newFilePath = "";
		if(isset($_FILES["fileModify"]) && $_FILES["fileModify"]["error"]==0)
		{
			$newFileTmp=$_FILES["fileModify"]["tmp_name"]; //새롭게 바뀐 첨부 파일 임시 파일 이름
			$newFileName=$_FILES["fileModify"]["name"]; //새롭게 바뀐 파일 이름
			move_uploaded_file($newFileTmp, $rootDir.$targetDir.$newFileName); //새로운 파일을 DB에 업데이트.
			$newFilePath=$targetDir.$newFileName;
		}

		//내가 코딩했을 경우 에는 SQL 쿼리문을 새로운 파일이 있으면 path를 추가 하였고,
		//새로운 파일이 없을때는 path 를 없이 UPDATE 해서 총 두번의 쿼리문을 작성하였다.
		//하지만 비효율적인 코드라서 아래와 같이 하나의 코드로 하였다.
		//if!(path가 비어있지 않고, 파일이 존재하지고, 그것이 파일이면!)
		// 파일을 삭제한다. 이것은 이미 첨부파일이 존재하면 예전 파일을 삭제하고 새로운 파일을 업데이트 하기 위한것이다.
		if(!empty($row["path"]) && file_exists($rootDir.$row["path"]) && is_file($rootDir.$row["path"]))
		{
			@unlink($rootDir.$row["path"]);//예전 파일 삭제.
		}
		//데이터베이스 업데이트.
		mysqli_query($conn, "
				UPDATE
					user
				SET
					name='".$userName."', title='".$userTitle."', content='".$userContent."', path='".$newFilePath."'
				WHERE
					idx='".$num."'
		;");


/*

		//비밀번호가 동일하면 업데이트

		if($_FILES["fileModify"]["error"]==0)
		{
			//비밀번호가 맞고 기존 파일이 존재한다면, 기본 파일을 삭제한다.
			//다음 새로운 경로를 받아 새로운 경로로 데이터베이스에 업데이트 한다.

			echo($_SERVER["DOCUMENT_ROOT"].$oldFilePath);


			exit;

		}
		else
		{
			mysqli_query($conn, "
					UPDATE
						user
					SET
						name='".$userName."', title='".$userTitle."', content='".$userContent."'
					WHERE
						idx='".$num."'
				;");
		} */
		mysqli_close($conn);
		header("location: /STUDY/board/read.php?pageNumber=$num");

	}
	else
	{
		mysqli_close($conn);
		?>
			<script type="text/javascript">
				alert("비밀번호가 틀립니다.");
				location.href = "/STUDY/board/read.php?pageNumber=<?=$num?>";
			</script>
		<?
	}
?>