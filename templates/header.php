
    <nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $category): ?>
            <li class="nav__item <?php if ($category["id"] === $cat): ?>nav__item--current<?php endif; ?>">
                <a href="all-lots.php?category_id=<?= $category["id"]; ?>"><?= $category["name_category"]; ?></a>
            </li>
            <?php endforeach; ?>
      </ul>
    </nav>


