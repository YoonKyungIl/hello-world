<?php
	include "DbConnect.php";  //데이터 베이스를 연결하는 php 파일을 include 하여 사용했다.
	$conn=dbconn(); //dbconn이란 함수를 호출하면 데이터 베이스 연결과 함께 내가 사용 할 테이블을 선택 하게 된다.
					//원래는 이렇게 함수 호출로 테이블을 하나만 선택 해서는 안된다. 왜냐하면 테이블이 여러개 일 경우에는 각각 따로 선택해야되기 때문이다.
			//var_dump($_GET);

	$number=$_GET["pageNumber"];

	$query="
		SELECT
			*
		FROM
			user
		WHERE
			idx='".$number."'
		";

	$result=mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$userFile=basename($row["path"],"name"); //read 에서 첨부된 파일이 있으면 파일명만 보여주려고 하는 변수
	$filePath=$row["path"];//경로에 파일이 있는지 검사하려고 만든 변수
	$path_parts=pathinfo($row["path"], PATHINFO_EXTENSION); //파일의 path 에서 파일이 있냐 없냐 확인하려는 함수
	//pathinfo 에는 여러가지 옵션이 있따 EXTENSION, BASENAME, DIRNAME
	//DIRNAME은 uploads, BASENAME은 wallpaper EXTENSION 은 jpg 이다.
	//경로는 /STUDY/board/uploads/파일이름.확장자
	$replyPageNumber = (@empty($_GET["replyPageNumber"])) ? 1 : $_GET["replyPageNumber"]; //댓글 페이지가 없으면 1 있으면 댓글페이지 넘버를 가져온다.
	?>
<html xml:lang="ko">
	<head>
		<title>게시글</title>
	</head>
	<body>
		<h1>글 내용</h1>
		<table border=1>
			<tr align="center">
				<td>글 번호</td>
				<td>이 름</td>
				<td>제 목</td>
				<td>날 짜</td>
			</tr>
			<tr align="center">
				<td><?=$row["idx"]?></td>
				<td><?=$row["name"]?></td>
				<td><?=$row["title"]?></td>
				<td><?=$row["date"]?></td>
			</tr>
			<tr align="center">
				<td colspan=4><?=$row["content"]?></td>
			</tr>
			<tr align="center">
				<td>첨부파일: </td>
			<?php

				if($path_parts != NULL)
				{
			?>
				<td colspan=3><a href="<?=$row["path"]?>"><?=$userFile?></td>
			</tr>
			<?
				}
				else
				{
			?>
				<td colspan=3>없음</td>
			<?
				}
            $startComment=0; //처음 댓글은 항상 0 이기 때문에 0부터 시작.
            $re_count=mysqli_query($conn,"
                    SELECT
                        count(*)
                    FROM
                        user_reply
                    WHERE
                        board_id='".$number."'
                        ;");
            $cnt = mysqli_fetch_row($re_count);
            $endComment = $cnt[0]; //댓글의 총 개수를 알아 보기 위한 변수.
            $result=mysqli_query($conn, "
            SELECT
                board_id, re_name, re_content, date
            FROM
                user_reply
            WHERE
                board_id='".$number."'
            ");
            // 댓글의 갯수를 가져오는 쿼리문
			?>
        </table>
			<!--댓글 시작-->
            <h3>Reply</h3>
            <?php

            $replySize=5;
            $replyNo=($replyPageNumber-1)*$replySize;
            $replyResult=mysqli_query($conn, "
    		SELECT
    			board_id, re_name, re_content, date, idx
    		FROM
    			user_reply
    		WHERE
    			board_id= '".$number."'
    		LIMIT
    			".$replyNo.", ".$replySize."
        	;");
                while($replyAssoc = mysqli_fetch_assoc($replyResult))
                {
                	//댓글이 있는 갯수 만큼 5번씩 반복하여 출력한다.
                    ?>
                   ----------------------------------------<br>
                   이름: <?=$replyAssoc["re_name"]?><br>
                   내용: <?=$replyAssoc["re_content"]?><br>
                   시간: <?=$replyAssoc["date"]?><br>
                   <a href="reply_modify.php?reNo=<?=$replyAssoc["idx"]?>">수정</a>
                   <a href="delete.php?type=reply&no=<?=$replyAssoc["idx"]?>">삭제</a><br>

            <?
                }
            ?>
                ----------------------------------------<br>
                <!--댓글 끝-->
            </tr>

        	<?php

        	$totalReply= $cnt[0];
        	$startReply=0;
        	$replyPageSize=10;
        	$currentReplyPage=$replyPageNumber;
        	$startReplyPage=floor(($currentReplyPage-1)/$replyPageSize)*$replyPageSize;
        	$endReplyPage=$startReplyPage+$replyPageSize;

        	//마지막 페이지가 토탈 페이지보다 크면 안됨.
        	$totalReplyPage=ceil($totalReply/$replySize);

        	//이전 댓글 페이지 출력
        	if($startReplyPage >= $replyPageSize)
        	{
        		$preReplyPage=$startReplyPage-1;
        	?>
        		<a href="read.php?pageNumber=<?=$number?>&replyPageNumber=<?=$preReplyPage?>"> < </a>

        	<?php
        	}

        	//<--댓글 페이지 출력 시작-->
        	for($i=$startReplyPage; $i<$endReplyPage; $i++)
        	{
        		$replyPageNumber=$i+1;
        		if($replyPageNumber <= $totalReplyPage )
        		{
			?>
	        		<a href="read.php?pageNumber=<?=$number?>&replyPageNumber=<?=$i+1?>"><?=$i+1?></a>
			<?php
        		}
        	}
       		?>
			<!--댓글 페이지 출력 끝-->
			<?php
			//다음 댓글 페이지 출력
			if($totalReplyPage > $endReplyPage)
				{
					$nextReplyPage=($endReplyPage + 1);
				?>
					<a href="read.php?pageNumber=<?=$number?>&replyPageNumber=<?=$nextReplyPage?>"> > </a>
				<?
				}
			?>

        <!--댓글의 내용을 reply_process.php로 보내서 DB에 저장하게 하기 위한 form-->
        <form name="replyFrm" action="reply_process.php?no=<?=$number?>" method="POST">
        <p>이름: <input type="text" name="comName" autocomplete="off" /></p>
        <p>비밀 번호: <input type="password" name="comPw" /></p>
        <p>내용 <textarea name="comContent"></textarea></p>
            <p><button type="submit">댓글 작성</button></p>
        </form>
        <!--form 끝-->
        <a href="index.php">목록</a>
        <a href="modify.php?no=<?=$row["idx"]?>">수정</a>
        <a href="delete.php?type=board&no=<?=$row["idx"]?>">삭제</a>

		<?php
			mysqli_close($conn);
		?>
	</body>
</html>