<?php include $_SERVER['DOCUMENT_ROOT']."/findsong/db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="/findsong/css/styles.css" />
</head>
<body>
    <table class="list">
        <thead>
            <tr>
                <th width="100">제목</th>
                <th width="100">아티스트</th>
                <th width="50">시간</th>
                <th width="50">발매일</th>
                <th width="100">앨범</th>
                <th width="100">장르</th>
            </tr>
        </thead>
        <?php
            if(isset($_GET['page']))
                $page = $_GET['page'];
            else
                $page = 1;
            // 테이블에서 id를 기준으로 내림차순해서 5개까지 표시
            $sql = mq("select * from songDB");
            $row_num = mysqli_num_rows($sql);
            $list = 5;
            $block_ct = 5;

            $block_num = ceil($page / $block_ct);
            $block_start = (($block_num - 1) * $block_ct) + 1;
            $block_end = $block_start + $block_ct - 1;

            $total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
            if($block_end > $total_page) $block_end = $total_page; // 만약 블록의 마지막 번호가 페이지 수보다 많다면 마지막 번호는 페이지 수
            $start_num = ($page - 1) * $list;

            $sql2 = mq("select * from songDB order by idx limit $start_num, $list");

            while($Slist = $sql2->fetch_array()) { // fetch_array() = 한 행식 패치하여 배열로 저장
            // 테이블에서 id를 기준으로 내림차순해서 5개까지 표시
                $sql = mq("select * from songDB order by idx desc limit 0,5");
                while($Slist = $sql->fetch_array()) {
                    // title 변수에 DB에서 가져온 title을 선택
                    $title = $Slist["title"];
                    if(strlen($title) > 30) {
                        // title이 30을 넘으면 ...표시
                        $title=str_replace($Slist["title"], mb_substr($Slist["title"], 0, 30, "utf-8")."...", $Slist["title"]);
                    }
        ?>
        <tbody>
                <tr>
                    <th width="150"><?php echo $Slist['title'] ?></th>
                    <th width="150"><?php echo $Slist['artist']?></th>
                    <th width="50"><?php echo $Slist['time'] ?></th>
                    <th width="50"><?php echo $Slist['date'] ?></th>
                    <th width="100"><?php echo $Slist['album'] ?></th>
                    <th width="100"><?php echo $Slist['genre'] ?></th>
                </tr>
        </tbody>
        <?php } ?>
    </table>
    <div id="page_num">
            <ul>
                <?php
                    if($page <= 1) // 현재 페이지가 1이라면
                        {echo "<li class='fo_re'>[<<]</li>";} // 링크 불가
                    else {echo "<li><a href='?page=1'>[<<]</a></li>";} // 1번 페이지로 링크        
                    for($i = $block_start; $i <= $block_end; $i++) {
                        // 초기값을 블록의 시작번호를 조건으로 블록 시작 번호가 마지막 블록보다 작거나 같을 때까지 반복
                        if($page == $i) {echo "<li class='fo_re'>[$i]</li>";} // 현재 페이지 표시
                        else {echo "<li><a href='?page=$i'>[$i]</a></li>";}
                    }
                    if($page >= $total_page) {echo "<li class='fo_re'>[>>]</li>";} // 마지막 페이지 표시
                    else {echo "<li><a href='?page=$total_page'>[>>]</a></li>";} // 마지막 페이지로 링크

                ?>
            </ul>
        </div>
    <?php } ?>
</body>
</html>