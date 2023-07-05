<form class="my-2 my-lg-0" id="formSearch">
  <!-- <input class="form-control mr-sm-2 w-full" value="<?= $_GET['search'] ?? "" ?>" name="search" type="search" placeholder="Search" aria-label="Search"> -->
  <input class="form-control mr-sm-2 w-full" name="search" type="search" placeholder="Search" aria-label="Search">

  <div class="d-flex justify-content-between mt-3">
    <div class="search-found">
      <p>Search found <b>
          <!-- <?= $count_search ?> -->
          <span id="count_search"></span>
        </b> results</p>
    </div>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCategory">
      <i class="fa fa-circle-plus"></i>
    </button>

  </div>
</form>

<table class="table mt-5">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Category name</th>
      <th scope="col">Operations</th>
    </tr>
  </thead>
  <tbody id="dataTable">
    <!-- <?= $tableCategoriesHTML ?> -->
  </tbody>
</table>


<nav id="dataPagination">
  <!-- <?= $paginationHTML ?> -->
</nav>

<!-- Modal Create Category -->

<div class="modal fade" id="createCategory" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createCategoryLabel">Add new category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= route('categories/store') ?>" method="POST" id="formCategoryNew">
        <div class="modal-body">

          <div class="mb-3">
            <label for="category_name">Category name</label>
            <input type="text" class="form-control" name="category_name" id="category_name">
            <span class="text-secondary">We'll never share your email with anyone else.</span> <br>
            <span class="text-danger" id="error-name"></span>
          </div>


          <div class="mb-3">
            <label for="parent_id">Parent category</label>
            <select name="parent_id" id="parent_id" class="form-control">
              <option value="">Uncategorized</option>
              <?php
              if (!empty($allCategories) && is_array($allCategories)) :
                foreach ($allCategories as $category) :  ?>
                  <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
              <?php endforeach;
              endif; ?>
            </select>
          </div>

        </div>
        <div class="modal-footer justify-content-start">
          <button type="submit" id="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Show Category -->

<div class="modal fade" id="showCategory" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showCategoryLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= route('categories/update') ?>" method="POST" id="formCategoryUpdate">
        <input type="hidden" name="category_id" id="category_id">

        <div class="modal-body">

          <div class="mb-3">
            <label for="category_name">Category name</label>
            <input type="text" class="form-control" name="category_name" value="11" id="category_name">
            <span class="text-secondary">We'll never share your email with anyone else.</span> <br>
            <span class="text-danger" id="error-name"></span>
          </div>

          <div class="mb-3">
            <label for="parent_id">Parent category</label>
            <select name="parent_id" id="parent_id" class="form-control">
              <option value="">Uncategorized</option>
              <?php
              if (!empty($allCategories) && is_array($allCategories)) :
                foreach ($allCategories as $category) :  ?>
                  <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
              <?php endforeach;
              endif; ?>
            </select>
          </div>

        </div>
        <div class="modal-footer justify-content-start">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts  -->
<script>
  function showCategory(categoryId) {
    $.ajax({
      method: "GET",
      // url: window.base_url + '/categories/show' ,
      url: '<?= route('categories/show') ?>',
      data: {
        category_id: categoryId
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('#formCategoryUpdate [name="category_id"]').val(response.data.id)
          $('#formCategoryUpdate [name="category_name"]').val(response.data.name)
          $('#formCategoryUpdate [name="parent_id"]').val(response.data.parent_id);
        }
      }
    });
  }

  function copyCategory(categoryId) {
    $.ajax({
      method: "GET",
      // url: window.base_url + '/categories/show' ,
      url: '<?= route('categories/show') ?>',
      data: {
        category_id: categoryId
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('#formCategoryNew [name="category_name"]').val(response.data.name)
          $('#formCategoryNew [name="parent_id"]').val(response.data.parent_id);

        }
      }
    });
  }

  function removeCategory(categoryId) {

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          method: "POST",
          // url: window.base_url + '/categories/delete' ,
          url: '<?= route('categories/delete') ?>',
          data: {
            category_id: categoryId
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              displayData();
              Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
            }
          }
        });
      }
    })

  }

  function displayData() {
    const url = new URL(window.location.href);
    const searchParams = new URLSearchParams(url.search);
    const searchValue = searchParams.get("search");
    const pValue = searchParams.get("p") || 1;
    $("[name='search']").val(searchValue)

    $.ajax({
      method: "GET",
      url: window.base_url + '/categories/data-html',
      data: {
        p: pValue,
        search: searchValue
      },
      dataType: "json",

      success: function(response) {
        $("#dataTable").html(response.tableHTML);
        $("#dataPagination").html(response.paginationHTML);
        $("#count_search").text(response.count_search);

        let htmlOption = `<option value="">Uncategorized</option>`;
        response.allCategories.forEach(element => {
          htmlOption += `<option value="${element.id}">${element.name}</option>`
        });

        $("#parent_id").html(htmlOption)

      },
      error: function(error) {
        console.error("Error:", error);
      }

    });
  }

  $(document).ready(function() {


    displayData();

    let formCategoryNew = $('#formCategoryNew');

    formCategoryNew.submit(function(e) {
      e.preventDefault();
      let dataCategoryNew = new FormData();

      dataCategoryNew.append("name", $('#formCategoryNew [name="category_name"]').val())
      dataCategoryNew.append("parent_id", $('#formCategoryNew [name="parent_id"]').val())
      $.ajax({
        method: "POST",
        url: formCategoryNew.attr('action'),
        data: dataCategoryNew,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
          if (response?.errors?.name) {
            $("#error-name").text(response.errors.name)
          }
          if (response.success) {
            displayData();
            formCategoryNew.trigger("reset");;
            $("#createCategory").modal('hide');

          }
        },
        error: function(error) {
          console.error("Error:", error);
        }

      });
    });


    let formCategoryUpdate = $('#formCategoryUpdate');
    formCategoryUpdate.submit(function(e) {
      e.preventDefault();

      let dataCategoryUpdate = new FormData();
      dataCategoryUpdate.append("category_id", $('#formCategoryUpdate [name="category_id"]').val())
      dataCategoryUpdate.append("name", $('#formCategoryUpdate [name="category_name"]').val())
      dataCategoryUpdate.append("parent_id", $('#formCategoryUpdate [name="parent_id"]').val())
      $.ajax({
        method: "POST",
        url: formCategoryUpdate.attr('action'),
        data: dataCategoryUpdate,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
          if (response?.errors?.name) {
            $("#error-name").text(response.errors.name)
          }
          if (response.success) {
            displayData();
            formCategoryUpdate.trigger("reset");;
            $("#showCategory").modal('hide');

          }
        },
        error: function(error) {
          console.error("Error:", error);
        }

      });
    });
  });
</script>