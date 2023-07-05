<?php

namespace App\Controllers;

use App\Core\DB;
use App\Core\View;
use App\Models\Category;

class CategoryController
{

  /**
   * Display a list of categories.
   *
   * @return View
   **/

  public string $htmlDataCategories = "";

  public function index(): View
  {

    $allCategories = Category::all();
    $data = [
      'allCategories' => $allCategories,
    ];

    return View::make('app', 'categories/index', $data);



    // $allCategories = Category::all();

    // $paginationNumber = $_GET['p'] ?? 1;

    // $categories = Category::pagination($paginationNumber);

    // $search = $_GET['search'] ?? "";
    // $count_search = 0;

    // if (!empty($search)) {
    //   $categories = Category::search($search);
    //   $count_search = count($categories);
    // }
    // $tableCategoriesHTML = $this->generateTableCategoriesHTML($categories);
    // $paginationHTML = $this->generatePaginationHTML(ceil(count($allCategories) / 10), $paginationNumber);

    // $data = [
    //   'allCategories' => $allCategories,
    //   'tableCategoriesHTML' => $tableCategoriesHTML,
    //   'paginationHTML' => $paginationHTML,
    //   'count_search' => $count_search
    // ];

    // return View::make('app', 'categories/index', $data);
  }


  /**
   * Generate data for categories HTML [tableHTML,allCategories,paginationHTML].
   *
   * @return string json_encode
   */
  public function generateDataCategoriesHTML()
  {


    $paginationNumber = $_GET['p'] ?? 1;
    $search = $_GET['search'] ?? "";
    $count_search = 0;

    $allCategories = Category::all();
    $categoriesPagination = Category::pagination($paginationNumber);
    $paginationTotal = ceil(count($allCategories) / 10);

    if (!empty($search)) {
      $dataSearch =  Category::search($search, $paginationNumber);
      $categoriesPagination =  $dataSearch['categoriesPagination'];
      $count_search =  $dataSearch['count_search'];
      $paginationTotal = ceil(count($dataSearch['categories']) / 10);
    }

    $tableCategoriesHTML = $this->generateTableCategoriesHTML($categoriesPagination);
    $paginationHTML = $this->generatePaginationHTML($paginationTotal, $paginationNumber);

    $response = [
      'tableHTML' => $tableCategoriesHTML,
      'allCategories' =>  $allCategories,
      'paginationHTML' => $paginationHTML,
      'count_search' => $count_search,
    ];

    return json_encode($response);
  }


  /**
   * Generate HTML for the categories table.
   *
   * @param array $categories
   * @param int|null $parent_id
   * @param int $level
   * @param int $index
   * @return string 
   */
  public function generateTableCategoriesHTML($categories, $parent_id = null, $level = 0, &$index = 1)
  {

    $html = &$this->htmlDataCategories;
    foreach ($categories as $category) {
      if ($category['parent_id'] == $parent_id) {
        $html .= "<tr class='level-{$level}'>";
        $html .= "<td scope='row'>" . $index . "</td>";
        $html .= "<td>" . str_repeat('—&nbsp;', $level) . $category['name'] . "</td>";
        $html .= '<td>
        <a href="#showCategory" data-toggle="modal"  data-target="#showCategory" onclick="showCategory(' . $category['id'] . ')"><i class="fa fa-pen-to-square"></i></a>
        <a href="#createCategory" data-toggle="modal" data-target="#createCategory" onclick="copyCategory(' . $category['id'] . ')"><i class="fa fa-copy"></i></a>
        <a href="javascript:void(0)" onclick="removeCategory(' . $category['id'] . ')"><i class="fa fa-trash"></i></a>
        </td>';
        $html .= "</tr>";
        $index++;
        $this->generateTableCategoriesHTML($categories, $category['id'], $level + 1, $index);
      }
    }

    if (empty($categories)) {
      $html .=  '<td colspan="3" class="text-center">Empty data categories</td>';
    }

    return $html;
  }

  /**
   * Generate HTML for pagination.
   *
   * @param int $paginationTotal
   * @param int $currentPagination
   * @return string
   */
  public function  generatePaginationHTML($paginationTotal, $currentPagination)

  {
    $paginationHTML = '<ul class="pagination justify-content-center">';
    $paginationNumber = 1;
    $querySearch = "";

    if (!empty($_GET['search'])) {
      $querySearch = "search=" . $_GET['search'] . "&";
    }

    if ($currentPagination > 1) {
      $previousPagination = $currentPagination - 1;
      $paginationHTML .= '<li class="page-item"><a class="page-link" href="' . route("categories?{$querySearch}p={$previousPagination}") . '">Previous</a></li>';
    }

    for ($index = 0; $index < $paginationTotal; $index++) {
      $paginationHTML .= '<li class="page-item ' . ($currentPagination == ($index + 1) ? 'active' : '') . '"><a class="page-link" href="' . route("categories?{$querySearch}p={$paginationNumber}") . '">' . $paginationNumber . '</a></li>';
      $paginationNumber++;
    }
    if ($currentPagination < $paginationTotal) {
      $nextPagination = $currentPagination + 1;
      $paginationHTML .= '<li class="page-item"><a class="page-link" href="' . route("categories?{$querySearch}p={$nextPagination}") . '">Next</a></li>';
    }

    $paginationHTML .= '</ul>';

    return $paginationHTML;
  }

  /**
   * Store a new category.
   *
   * @return string json_encode
   */
  public function store(): string
  {
    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

      $name = check_string($_POST['name'] ?? '') ?? '';
      $parent_id = $_POST['parent_id'] ?? null;

      $category = DB::get_row("SELECT * FROM  `categories` WHERE `categories`.`name` = '{$name}'");

      if (!empty($category)) {
        $response['errors']['name'] = "The category name already exists.";
        return json_encode($response);
      }

      if (empty($name)) {
        $response['errors']['name'] = "Please enter the category name.";
        return json_encode($response);
      }


      $dataStore = array();
      $dataStore['name'] = $name;
      if ($parent_id != null) {
        $dataStore['parent_id'] = $parent_id;
      }

      $category = Category::create($dataStore);

      if ($category) {
        $response['data'] = $dataStore;
        $response['success'] = "Category added";
        return json_encode($response);
      }

      return json_encode(['msg' => "Category not added"]);
    }
  }

  /**
   * Show a category.
   *
   * @return json_encode
   */
  public function show(): string
  {

    $response = array();

    $id = $_GET['category_id'] ?? null;

    if ($id == null) {
      $response['errors']['category_id'] = "Please enter the category id";
      return json_encode($response);
    }

    $categoryById = Category::findById($id);

    if (!$categoryById) {
      $response['errors']['id'] = "Invalid id";
      return json_encode($response);
    }

    $response['data'] = $categoryById;

    return json_encode($response);
  }

  /**
   * Update a category.
   *
   * @return string json_encode
   */
  public function update(): string
  {


    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $id = $_POST['category_id'] ?? null;
      $categoryById = Category::findById($id);

      if (!$categoryById) {
        $response['errors']['id'] = "Invalid id";
        return json_encode($response);
      }

      $name = check_string($_POST['name'] ?? '') ?? '';
      $parent_id = $_POST['parent_id'] ?? null;


      $category = DB::get_row("SELECT * FROM  `categories` WHERE `categories`.`name` = '{$name}'");

      if (!empty($category)) {
        $response['errors']['name'] = "The category name already exists.";
        return json_encode($response);
      }

      if (empty($name)) {
        $response['errors']['name'] = "Please enter the category name.";
        return json_encode($response);
      }

      $dataUpdate = array();
      $dataUpdate['name'] = $name;

      if ($parent_id != null) {
        $dataUpdate['parent_id'] = $parent_id;
      }

      $category = Category::update($id, $dataUpdate);

      $response['data'] = $category;
      $response['success'] = "Category updated";

      return json_encode($response);
    }
  }

  /**
   * Delete a category.
   *
   * @return string json_encode
   */
  public function delete(): string
  {


    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

      $id = $_POST['category_id'] ?? null;
      $categoryById = Category::findById($id);

      if (!$categoryById) {
        $response['errors']['id'] = "Invalid id";
        return json_encode($response);
      }

      $isDelete =   Category::delete($id);

      if ($isDelete) {
        $response['success'] = "Category deleted";
        return json_encode($response);
      }

      return json_encode(['msg' => "Category not deleted"]);
    }
  }
}
