<?php
require_once('sqlite_connect.php');



session_start();
$csrf =  base64_encode( openssl_random_pseudo_bytes(32));
$_SESSION['csrf'] = $csrf;



$stmt = $pdo->prepare('SELECT * FROM images ORDER BY created_at DESC');
$stmt->execute();

$html = '';
$images_count = 0;
while ($results = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $image_title = htmlspecialchars($results['image_title'], ENT_QUOTES);
    $created_at = date('Y年n月j日 H:i', $results['created_at']);

    $html .= <<<EOD
    <tr>
    <td class="td_image"><img src="images/{$results['image_name']}"></td>
    <td>{$image_title}</td>
    <td>{$created_at}</td>
    </tr>
    EOD;

    $images_count++;
}

if ($images_count < 1) {
    $html .= '<tr><td colspan="3">まだ投稿がありません</td></tr>';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPとSQLiteを使って画像を投稿するシンプルな例</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>画像の投稿</h1>

    <form action="post.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
        <div>画像（1MB以下のjpgかpng）<br><input id="post_image" type="file" name="post_image" accept=".jpg,.jpeg,.JPG,.JPEG,.png,.PNG" required></div>
        <div><img id="preview_image" src=""></div>
        <div>画像タイトル <input type="text" name="image_title" maxlength="30" placeholder="30文字以内"></div>
        <div><input id="submit" type="submit" value="送信"></div>
        <input type="hidden" name="csrf" value="<?=$csrf?>">
    </form>

    <table>
        <tr>
            <th>画像</th>
            <th>画像タイトル</th>
            <th>投稿日時</th>
        </tr>
        <?=$html?>
    </table>

    <script src="assets/js/script.js"></script>
</body>
</html>