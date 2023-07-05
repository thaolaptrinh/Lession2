<?php

namespace App\Models;

use App\Core\DB;


interface CategoryInterface
{
  public static function all();
  public static function findById($id);
  public static function create($data);
  public static function update($id, $data);
  public static function delete($id);
}


class Category implements CategoryInterface
{

  public static function all()
  {
    $categories = DB::get_list("SELECT * FROM `categories` ORDER BY `categories`.`id` ASC ");

    return $categories;
  }

  public static function pagination($paginationNumber = 1, $limit = 10)
  {
    $offset = $paginationNumber <= 1 ? 0 : ($paginationNumber - 1) * 10;

    $categories = DB::get_list("SELECT * FROM `categories` ORDER BY `categories`.`id` ASC LIMIT $offset, $limit ");

    return $categories;
  }

  public static function search($search, $paginationNumber, $limit = 10)
  {
    $offset = $paginationNumber <= 1 ? 0 : ($paginationNumber - 1) * 10;

    $categoriesPagination = DB::get_list("SELECT * FROM `categories` WHERE `categories`.`name` LIKE '%$search%' ORDER BY `categories`.`id` ASC LIMIT $offset, $limit ");

    $categories = DB::get_list("SELECT * FROM `categories` WHERE `categories`.`name` LIKE '%$search%'");
    return [
      'categoriesPagination' => $categoriesPagination,
      'categories' => $categories,
      'count_search' => count($categories)
    ];
  }


  public static function findById($id)
  {
    $category = DB::get_row("SELECT * FROM `categories` WHERE `categories`.`id` = {$id}");

    return $category;
  }
  public static function create($data)
  {

    if (!empty($data['parent_id'])) {
      return DB::insert('categories', [
        'name' => $data['name'],
        'parent_id' => $data['parent_id'],
      ]);
    }
    return DB::insert('categories', [
      'name' => $data['name'],
    ]);
  }
  public static function update($id, $data)
  {

    return DB::update('categories', $data, "id = {$id}");
  }
  public static function delete($id)
  {
    return DB::remove('categories', "id = {$id}");
  }
}
