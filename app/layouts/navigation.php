<ul class="nav nav-pills">
  <li class="nav-item">
    <a class="nav-link <?= !active_route(route('')) ?: 'active' ?>" href="<?= route('') ?>">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= !active_route(route('products')) ?: 'active'  ?>" href="<?= route('products') ?>">Products</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= !active_route(route('categories')) ?: 'active' ?>" href="<?= route('categories') ?>">Categories</a>
  </li>
</ul>