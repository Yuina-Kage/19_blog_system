<?php

require_once('config.php');

function getPostsFindByCategoryId($category_id)
{
$dbh = connectDb();  

$sql = <<<SQL
select
  p.*,
  c.name,
  u.name as user_name
from
  posts p
left join
  categories c
on p.category_id = c.id 
left join
  users u 
on p.user_id = u.id
SQL;  

// カテゴリーidの条件付加
if (
  isset($category_id) &&
  is_numeric($category_id)
) {
  $sql_where = " where category_id = :category_id";
} else {
  $sql_where = "";
}

$sql_order = " order by created_at desc";

//SQL結合
$sql = $sql . $sql_where . $sql_order;
$stmt = $dbh->prepare($sql);
// カテゴリーが指定されていた場合
if (
  isset($category_id) &&
  is_numeric($category_id)
) {
  $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
}

$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}