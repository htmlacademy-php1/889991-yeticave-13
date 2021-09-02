  <main>
    <nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= $category["name_category"]; ?></a>
            </li>
            <?php endforeach; ?>
      </ul>
    </nav>
    <section class="lot-item container">
            <h2>403 Forbidden</h2>
            <p>У вас нет прав доступа к содержимому этой страницы</p>
    </section>
  </main>

</div>

