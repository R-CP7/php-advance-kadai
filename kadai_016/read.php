<?php
$dsn ='mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user='root';
$password='root';

try{
  $pdo = new PDO($dsn, $user, $password);

    // orderパラメータの値が存在すれば（並び替えボタンを押したとき）、その値を変数$orderに代入する

  if(isset($_GET['order'])){
    $order = $_GET['order'];
  } else {
    $order = NULL;
  }

  if(isset($_GET['keyword]'])){
    $keyword = $_GET['keyword'];

  } else{
    $keyword = NULL;
  }
  
   // orderパラメータの値によってSQL文を変更する    
   if ($order === 'desc') {
    $sql_select = 'SELECT * FROM books WHERE  book_name LIKE :keyword ORDER BY updated_at DESC ';
  } else {
    $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at ASC';
  }

$stmt_select = $pdo->prepare($sql_select);

$partial_match = "%{$keyword}%";
$stmt_select->bindValue(':keyword', $partial_match, PDO:: PARAM_STR);

$stmt_select->execute();

$books =$stmt_select ->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e){
  exit ($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial scale=1.0">
    <title>書籍管理アプリ</title>
    <link rel="stylesheet" href="css/style.css">

    <link rel="preconnect" href="http://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
  </head>

<body>
  <header>
    <a href="php_book_app.php">籍管理アプリ</a>
</header>
<main>
  <article class="books">
    <h1>書籍一覧</h1>
    <?php

    // （商品の登録・編集・削除後）messageパラメータの値を受け取っていれば、それを表示する
    if(isset($_GET['message'])){
      echo"<p class='sucess'>{$_GET ['message']}</p>";
    }
    ?>
    <div class="books-ui">
      <div>
      <a href="read.php?order=desc&keyword=<?= $keyword ?>">
      <img src="images/desc.png" alt="降順に並び替え" class="sort-img">
       </a>
       <a href="read.php?order=asc&keyword=<?= $keyword ?>">
     <img src="images/asc.png" alt="昇順に並び替え" class="sort-img">
      </a>
      <form action ="read.php" method="get" class="search-form">
        <input type="hidden" name="order" value="<?= $order ?>">
        <input type="text" class="search-box" placeholder="書籍名で検索" name="keyword" value="<?= $keyword ?>">

</form>
    </div>
    <!-- ここに並び替えボタンと検索ボックスを作成する -->
   <a href="create.php" class="btn">書籍登録</a>
             </div>
             <table class="books-table">
                 <tr>
                     <th>書籍コード</th>
                     <th>書籍名</th>
                     <th>単価</th>
                     <th>在庫数</th>
                     <th>ジャンルコード</th>
                     <th>編集</th>
                     <th>削除</th>


                 </tr>
                 <?php

//配列の中身を順番に取り出し、表形式で出力する
foreach($books as $book) {
  $table_row = "
  <tr>
  <td>{$book['book_code']}</td>
  <td>{$book['book_name']}</td>
  <td>{$book['price']}</td>
   <td>{$book['stock_quantity']}</td>
   <td>{$book['genre_code']}</td> 
   <td><a href='update.php?id={$book['id']}'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td> 
   <td><a href='delete.php?id={$book['id']}'><img src='images/delete.png' alt='削除' class='delete-icon'></a></td>                        
                       
    </tr> 
  ";
  echo $table_row;
}
?>
    </table>
</article>
</main>
<footer>
<p class="copyright">&copy; 商品管理アプリ All rights reserved. </p>
</footer>
</body>
</html>
