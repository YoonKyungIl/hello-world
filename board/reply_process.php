<?php
    include "DbConnect.php";
    $conn=dbconn();

    $reply_idx=$_GET["no"];
    $c_name=$_POST["comName"];
    $c_pw=$_POST["comPw"];
    $c_content=$_POST["comContent"];
    $c_date=date("Y-m-d H:i:s");


    //작성한 댓글 게시글 번호에 맞추어서 저장
    mysqli_query($conn, "
				INSERT INTO
                    user_reply
				    (board_id, re_name, pw, re_content, date)
				VALUES
					('".$reply_idx."' , '".$c_name."', '".$c_pw."', '".$c_content."','".$c_date."');
				");
    mysqli_close($conn);
    header("location: /STUDY/board/read.php?pageNumber=$reply_idx");
?>