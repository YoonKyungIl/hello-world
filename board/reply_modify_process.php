<?php
	include "DbConnect.php";
	$conn=dbconn();

	$reNo=$_GET["reNo"];
	$reName=$_POST["reName"];
	$rePw=$_POST["rePw"];
	$reContent=$_POST["reContent"];
	$reDate=date("Y-m-d H:i:s");

	$result=mysqli_query($conn, "
		SELECT
			pw, board_id
		FROM
			user_reply
		WHERE
			idx=$reNo
	;");
	$row=mysqli_fetch_assoc($result);
	$boardNumber=$row["board_id"];//댓글이 있던 게시판으로 돌아가기 위한 게시판 번호

	if($rePw == $row["pw"])
	{
		mysqli_query($conn, "
				UPDATE
					user_reply
				SET
					re_name='".$reName."', re_content='".$reContent."', date='".$reDate."'
				WHERE
					idx='".$reNo."'
		;");
		header("location: /STUDY/board/read.php?pageNumber=$boardNumber");
	}
	else
	{
		mysqli_close($conn);
		?>
			<script type="text/javascript">
				alert("비밀번호가 틀립니다.");
				location.href = "/STUDY/board/read.php?pageNumber=<?=$boardNumber?>";
			</script>
		<?
	}
?>