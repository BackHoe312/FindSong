<?php include $_SERVER['DOCUMENT_ROOT']."/findsong/db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="./css/styles.css" />
    <link rel="stylesheet" type="text/css" href="./css/filter.css" />
    <link rel="stylesheet" type="text/css" href="./css/index.css" />
    
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <a href="/findsong/index.php">
                    <h1>Find Song Song</h1>
                    <h1 class="logoh1">파 송 송</h1>
                </a>
            </div>
            <form action="/findsong/index.php" method="GET">
                <select name="category">
                    <option value="title">제목</option>
                    <option value="artist">아티스트</option>
                </select>
                <input type="text" name="search" size="40" required="required" /> <button>검색</button>
            </form>
        </div>
    </header>
    <div class="genre">
        <ul>
            <li><input type="checkbox" name="genre" value="발라드">발라드</li>
            <li><input type="checkbox" name="genre" value="댄스">댄스</li>
            <li><input type="checkbox" name="genre" value="랩/힙합">랩/힙합</li>
            <li><input type="checkbox" name="genre" value="R&B/Soul">R&B/Soul</li>
            <li><input type="checkbox" name="genre" value="인디음악">인디음악</li>
            <li><input type="checkbox" name="genre" value="록/메탈">록/메탈</li>
            <li><input type="checkbox" name="genre" value="트로트">트로트</li>
            <li><input type="checkbox" name="genre" value="포크/블루스">포크/블루스</li>
            <li><button class="filter_btn" onclick="value_check()">필터 적용</button></li>
        </ul>
    </div>
    <div class="main">
    <table class="list">
        <thead>
            <tr>
                <th width="120">이미지</th>
                <th width="150">제목</th>
                <th width="150">아티스트</th>
                <th width="50">시간</th>
                <th width="100">발매일</th>
                <th width="150">앨범</th>
                <th width="100">장르</th>
            </tr>
        </thead>
        <?php
            if(isset($_GET['page']))
                $page = $_GET['page'];
            else
                $page = 1;
            // 테이블에서 id를 기준으로 내림차순해서 5개까지 표시
            if(isset($_GET['search'])) {
                $category = $_GET['category'];
                $search_con = $_GET['search']; ?>
                <h1>'<?php echo $search_con; ?>' 검색결과</h1><?php
                $sql = mq("select * from songDB where $category like '%$search_con%' order by idx");
            }
            else {
                $sql = mq("select * from songDB");
            }
            $row_num = mysqli_num_rows($sql);
            $list = 5;
            $block_ct = 5;

            $block_num = ceil($page / $block_ct);
            $block_start = (($block_num - 1) * $block_ct) + 1;
            $block_end = $block_start + $block_ct - 1;

            $total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
            if($block_end > $total_page) $block_end = $total_page; // 만약 블록의 마지막 번호가 페이지 수보다 많다면 마지막 번호는 페이지 수
            $total_block = ceil($total_page / $block_ct);
            $start_num = ($page - 1) * $list;
            if(isset($_GET['search'])) {
                $sql2 = mq("select * from songDB where $category like '%$search_con%' order by idx limit $start_num, $list");
            }
            else {
                $sql2 = mq("select * from songDB order by idx limit $start_num, $list");
            }           

            while($Slist = $sql2->fetch_array()) { // fetch_array() = 한 행식 패치하여 배열로 저장
            // 테이블에서 id를 기준으로 내림차순해서 5개까지 표시
            // $time = date("i:s", timestamp($Slist['time']));
    ?>
        <tbody>
                <tr>
                    <th width="120"><img src="./img/<?php echo $Slist['img'] ?>.jpg" style="width: 120px"></th>
                    <th width="150"><?php echo $Slist['title'] ?></th>
                    <th width="150"><?php echo $Slist['artist']?></th>
                    <th width="80"><?php echo $Slist['time'] ?></th>
                    <th width="100"><?php echo $Slist['date'] ?></th>
                    <th width="150"><?php echo $Slist['album'] ?></th>
                    <th width="100"><?php
                        echo $Slist['genre']; ?><br><?php 
                        if($Slist['genre2'] != '') 
                            echo $Slist['genre2']; ?>
                </tr>
        </tbody>
    <?php   } ?>
    </table>
    <div id="page_num">
            <ul>
                <?php
                    if($page <= 1)
                    { //만약 page가 1보다 크거나 같다면
                      echo "<li class='fo_re'>[<<]</li>"; //[<<]이라는 글자에 빨간색 표시 
                    }else{
                      echo "<li><a href='?page=1'>[<<]</a></li>"; //알니라면 [<<]글자에 1번페이지로 갈 수있게 링크
                    }
                    if(!($page <= 1))
                    { //만약 page가 1보다 크거나 같다면 빈값
                        $pre = $page-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
                        echo "<li><a href='?page=$pre'>[<]</a></li>"; //이전글자에 pre변수를 링크한다. 이러면 이전버튼을 누를때마다 현재 페이지에서 -1하게 된다.
                    }
                    for($i=$block_start; $i<=$block_end; $i++){ 
                      //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
                      if($page == $i){ //만약 page가 $i와 같다면 
                        echo "<li class='fo_re'>[$i]</li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
                      }else{
                        echo "<li><a href='?page=$i'>[$i]</a></li>"; //아니라면 $i
                      }
                    }
                    if($block_num >= $total_block){ //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
                    }else{
                      $next = $page + 1; //next변수에 page + 1을 해준다.
                      echo "<li><a href='?page=$next'>[>]</a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
                    }
                    if($page >= $total_page){ //만약 page가 페이지수보다 크거나 같다면
                      echo "<li class='fo_re'>[>>]</li>"; //마지막 글자에 긁은 빨간색을 적용한다.
                    }else{
                      echo "<li><a href='?page=$total_page'>[>>]</a></li>"; //아니라면 마지막글자에 total_page를 링크한다.
                    }

                ?>
            </ul>
    </div>
    </div>
</body>
</html>