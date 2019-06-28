<?php
	include "DbConnect.php";
	$conn=dbconn();

	//$num=$_GET["no"]; //delete.php 에서 idx 를 get 방식으로 보내서 num 변수에 저장.
	$pw=$_POST["pw"]; //deltet.php 에서 pw 를 Post 방식으로 보내서 pw 변수에 저장.
	$num = $_POST["number"];
	$type = $_POST["type"];

	//게시판인지 댓글인지 알아야 한다.
	//type 로 게시글이면 board 이고 댓글이면 reply 이다
	//no 는 board일때는 board의 idx 가 오고
	//reply 이면 reply의 idx 가 온다.
	//비밀번호가 틀릴 경우. 게시글과 댓글 두 곳에서 다 사용됨. 그래서 함수로 만들면 좋을거 같음.
	//
	function pw_wrong($var_board)
	{
	?>
		<script type="text/javascript">
			alert("비밀번호가 틀립니다.");
			location.href = "/STUDY/board/read.php?pageNumber=<?=$var_board?>";
		</script>
	<?
	}

	switch($type) {
		case "reply":
			// type = reply 관련된 삭제 처리
			$query="
				SELECT
					pw, idx, board_id
				FROM
					user_reply
				WHERE
					idx='".$num."'
			";
			$result=mysqli_query($conn, $query);
			$row=mysqli_fetch_assoc($result);
			// 위의 query는 댓글의 idx 와 비밀번호를 가져온다.
			// 댓글 삭제는 댓글마다 idx 가 고유하기 때문에 user_reply 테이블에서
			// $no 가 일치하는 컬럼을 삭제 해 주면 댓글 삭제가 완료된다.
			// pw 를 가져와야 하는 이유는 댓글에도 비밀번호가 일치해야 삭제되기 때문에 확인을 해야한다.
			// board_id 를 가져오는 이유는 비밀번호가 틀렸을 경우에 그 게시글로 이동시키기 위함이다.
			$returnBoard=$row["board_id"];
			if($pw === $row["pw"]) //0000 과 00000 은 == 비교구문으로 같다고 나온다. 형(type)까지 확인하여 오류의 오차를 줄인다.
			{
				//비밀번호가 일치하면 user_reply 테이블에서 idx 가 일치하는 컬럼을 삭제해준다.
				$del_reply="
				DELETE FROM
					user_reply

				WHERE
					idx='".$num."'
				";
				mysqli_query($conn, $del_reply);
			}
			else
				pw_wrong($returnBoard);

			header("location: /STUDY/board/read.php?pageNumber=$returnBoard");
		break;

		case "board":
			$query="
				SELECT
					pw, path, idx

				FROM
					user
				WHERE
					idx='".$num."'
			";
			$result=mysqli_query($conn, $query);
			$row=mysqli_fetch_assoc($result);

			if($pw === $row["pw"])
			{
				//비밀번호가 일치하면 user 테이블에서 idx 가 일치하는 컬럼을 삭제해준다.
				//게시글을 삭제할 경우에는 댓글 먼저 삭제한 후에 게시글을 삭제한다. 또는 cascade.
				//user 테이블의 idx 값을 user_reply 테이블의 board_id가 참조하게 하였다.
				//즉,  user_reply 테이블의 board_id는 외래키가 된다.
				//board_id 가 참조하는것은 user 테이블의 idx이다.
				//외래키를 만든 이유는 user 테이블의 컬럼이 삭제되면 삭제된 컬럼과 연결된 user_reply의 컬럼도 삭제하게 하려고 한것이다.
				//다시말하면 게시글이 삭제되면 그 게시글에 달린 댓글들도 삭제 되게 하려고 한것이다.
				//delete 옵션을 cascade 로 줘서 같이 삭제 되게 하였다.
				//cascade 의 단점.

				@unlink($_SERVER["DOCUMENT_ROOT"].$row["path"]);
				$del_board="
					DELETE FROM
						user
					WHERE
						idx='".$num."'
				";
				mysqli_query($conn, $del_board);
				header("location: /STUDY/board/index.php");
			}
			else
				pw_wrong($row["idx"]);
		break;
	}

/*
	//$num=(@empty($_GET["no"])) ? $_GET["reNo"] : $_GET["no"]; //게시글 삭제인지 리플 삭제인지 구분하는 조건문
	$noGet=$_GET["no"];
	$reNoGet= $_GET["reNo"];


		default:
			//게시글 삭제

			break;

	if(!empty($noGet))
	{
		//데이터 베이스에서 패스워드를 가지고 와야한다.
		$query="
			SELECT
				pw, path, idx
			FROM
				user
			WHERE
				idx='".$noGet."'
		";

	}
	//게시글 쿼리
	//GET 으로 no를 받아오면 게시글이란 소리다. 그래서 게시글 삭제를 위한 쿼리문을 쓴다.
	//받아온 값이 reNo이면 댓글이다. 댓글 쿼리를 사용한다.
	else
	{
		$query="
		SELECT
			pw, idx
		FROM
			user_reply
		WHERE
			idx='".$reNoGet."'
		";

	}//댓글 쿼리

	$result=mysqli_query($conn, $query);
	$row=mysqli_fetch_assoc($result);
	if($pw == $row["pw"])
	{

		//path 가 존재하면 파일이 존재하고 게시글을 삭제해야한다.
		//그러므로 path 가 존재하는지 검사한다.
		//존재하면 게시글이므로 업로드 폴더 경로에 있는 파일을 삭제하고
		//그렇지 않으면 댓글이므로 댓글을 삭제한다.
		if(isset($row["path"]))
		{

			if(!empty($row[path]))
			@unlink($_SERVER["DOCUMENT_ROOT"].$row["path"]); //경고를 없애줘야한다.
			//path 가 존재하거나 path가 Null 이라면 게시글이다.
			//댓글 테이블에는 path 라는 컬럼이 존재하지 않는다.
			//그러므로 path 만 확인해줘도 게시글인지 댓글인지 알 수 있다.



		//절대 경로 지정.
		//D:/Autoset/AutoSet10/public_html/board/uploads/wallpaper4.jpg
		//이렇게 나온다.
			$del="
				DELETE FROM user
				WHERE idx='".$noGet."'
			";
			mysqli_query($conn, $del);

			$del_reply="
				DELETE FROM user_reply
				WHERE board_id='".$row["idx"]."'
			";
			mysqli_query($conn, $del_reply);//댓글도 삭제해야함 게시글이 삭제되었기때문에
		}
		else
		{
			//게시글이 아니라 댓글이라면, 댓글을 삭제해야한다.
			$del="
				DELETE FROM user_reply
				WHERE idx=$reNoGet
			";
			mysqli_query($conn, $del);

		}

		mysqli_close($conn);
		header("location: /STUDY/board/index.php");
	}
	else
	{
		?>
			<script type="text/javascript">
				alert("비밀번호가 틀립니다.");
				location.href = "/STUDY/board/index.php";
			</script>
		<?
	}*/
 ?>