<!DOCTYPE html>

<?php
	include "DbConnect.php";
	$conn=dbconn();

	echo "commit test"
	$pageSize='5'; // 한 페이지에 보여질 게시글 수
	$pageTotal=mysqli_query($conn , "SELECT count(*) FROM user;");//전체게시글의 수
	$pageTotalRow=mysqli_fetch_row($pageTotal);
	$pageTotalSize=$pageTotalRow[0]; //총  게시글이 나온다.
	$totalPageNumber=ceil($pageTotalSize/$pageSize);// 전체 게시 글의 수/한 페이지당 보여질 글의 수
	//if(!isset($_GET["pageNumber"])) $_GET["pageNumber"] = 1;
	//$pageNumber=(isset($_GET["pageNumber"]) && $_GET["pageNumber"]) ? $_GET["pageNumber"] : NULL;
	$startPage=0; //시작 페이지 0 부터 시작
	$endPage=0; // 마지막 페이지 0 부터 시작
	$pageListSize=10; //총 보여질 페이지 리스트의 수   게시글 보면 밑에 몇번째 페이지를 표현하고있는지 보여주는 것. 보통 10개씩 보여준다.
	$pageNumber = (@empty($_GET["pageNumber"])) ? 1 : $_GET["pageNumber"]; //pageNumber가 비어있으면 1 아니면 pageNumber를 가져온다.

	//if($pageNumber == NULL)
	//{
	//	$pageNumber=0;
	//}

	/*if(isset($pageNumber) || $pageNumber<0) //페이지넘버의 값이 잘못넘어오거나 0보다 작은 수가 올수 있으므로 조건문으로 0으로 해주어야한다.
		$pageNumber=0;

		$pageNumber=(($pageNumber-1)*$pageSize);


		debug(
				array("totalPageNumber" => $totalPageNumber, "endPage" => $endPage)
			);



	//$testPageNumber=$_GET["testPageNumber"];
/*
	$query="
		SELECT
			idx, name, title, date, content, pw
		FROM
			user
		ORDER BY
			idx desc
		LIMIT
			".$testPageNumber.", ".$pageSize."
	";
	//목록을 가져오기 위한 쿼리. 현재 페이지의 게시글을 가져오려는 쿼리.
	//예를 들면 5번째 페이지의 글을 보고 있을때 5개의 글을 가져와야 하는데 시작 글과 끝을 표시해 주기 위함이다.
	//몇번째 페이지를 보고 있을지 모르기 때문에 변수로 설정해 두어야 한다. 고정된 값을 주어버리면 페이지를 이동해도 계속 같은 게시글만 나오게 된다.
	//LIMIT 는 제한을 두는 것. 페이지 넘버 부터 페이지 사이즈까지의 데이터만 보내달라는 것.
	$result=mysqli_query($conn, $query); //데이터 베이스에 쿼리에 대한 값을 달라고 요청하는것.
*/
	//현재 페이지 설정
	//현재 페이지가 10이라면 -5에서 +5 까지 해서 5부터 15 까지 페이지를 보여주면 된다.

	$currentPage=$pageNumber; //현재 페이지
	$startPage=floor(($currentPage - 1)/$pageListSize)*$pageListSize; //시작 페이지
	$endPage=$startPage+$pageListSize; //마지막 페이지
	/*
	if($pageTotalSize<$endPage)
		{
			$endPage=$pageTotalSize;
		}*/
?>


<html lang="ko">
<head>
	<title>게시판</title>
</head>
	<body>

	<h1>게시판</h1>
	<h3><? echo "총 게시글 ".$pageTotalRow[0]."개"?></h3>
		<table>
			<tr>
				<td>글 번호</td>
				<td>제 목</td>
				<td>이 름</td>
				<td>날 짜</td>
			</tr>
		<?php

		//쿼리문에서 LIMIT 옵션을 주면 데이터를 제한적으로 가져 올 수 있다.
		//LIMIT a, b 하면 a 에서부터 b 까지의 데이터만 받아오게다는 말이다.

		$no=($pageNumber-1)*$pageSize;
		//$no값에 이러한 수식을 넣은 이유는 각 페이지가 변하여도 데이터를 5개씩만 가져와야 하기때문에
		//1페이지는 0부터 4까지의 데이터만 가져와야하고
		//2페이지는 5부터 9까지의 데이터를 가져와야한다.
		//첫 번째 페이지부터 마지막 페이지까지 가져오는 쿼리 5개씩 가져오려는 쿼리
		$query="
				SELECT
		 			idx, title, name, date
		 		FROM
					user
				ORDER BY
					idx desc
				LIMIT
					".$no.", ".$pageSize."
		 		";
		?>

		<?php
			$result=mysqli_query($conn, $query);
			while ($row = mysqli_fetch_assoc($result))
			{
		?>
				<tr align="center">
					<td><?=$row["idx"]?></td>
					<td><a href="read.php?pageNumber=<?=$row["idx"]?>"><?=$row["title"]?></a></td>
					<td><?=$row["name"]?></td>
					<td><?=$row["date"]?></td>
				</tr>

		<?php
			}
		?>
		<tr>
		<td colspan=4>

		<?php
				//이전 페이지
				if($startPage >= $pageListSize)
				{
					$prePageList=($startPage-1); // pageSize 곱해주는 이유는 pageNumber 값을 바꿔주기 위해서이다.
		?>
					<a href="./?pageNumber=<?=$prePageList?>"> < </a>

		<?php
				}

			//페이지 출력한다.
		?>
			<?php
				for($i=$startPage; $i<$endPage; $i++)
				{
					$pageNumber=$i+1;
					//$page=$pageSize*$i; //페이지 값을 pageNumber값으로 변환.
					//pageNumber 는 0부터 시작함으로 1부터 바꾸어준다. 안그러면 0 1 2 3 .. 10 이런식으로 나오기 때문이다.
					//startPage 는 0부터 시작이기 때문에 아래에서 보면 i + 1을 한것이다 그래야 페이지 숫자가 1 부터 나오기 때문이다.
					//endPage 까지 출력해야한다. endPage가 totalPagaNumber보다 많으면 안된다
					//예를 들면 totalPageNumber 이 19 라면 endPage가 20까지 출력되면 안된다.
					//그래서 pageNumber이 totalPageNumber 보다 크면 안되기 때문에 조건을 아래와 같이 했다.
					if($pageNumber <= $totalPageNumber)
					{
			?>
						<a href="./?pageNumber=<?=$i+1?>"><?=$i+1?></a>

			<?php
					}
				}

			//다음 페이지
			//다음 페이지는 총 페이지가 마지막 리스트 보다 클때이다.
			//10개의 페이지를 다 보여주고도 페이지가 남았을때 다음페이지를 보여주어야 한다.
			//다음페이지는 마지막 페이지의 바로 다음 페이지가 된다.
			if($totalPageNumber > $endPage)
			{
				$nextPageList=($endPage + 1);
			?>
				<a href="./?pageNumber=<?=$nextPageList?>"> > </a>
			</td>
			</tr>
		<?php
			}
		?>
		</table>
		<p><a href="write.php">글 쓰기</a></p>
		<?php
			mysqli_close($conn);
		?>

	</body>
</html>