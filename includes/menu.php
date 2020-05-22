<?php $currentPage = basename($_SERVER['SCRIPT_FILENAME']); ?>
<nav>
  <ul>
   <li><a href="index.php" <?php if ($currentPage == 'index.php') {echo 'id="here"'; } ?>>Startpagina</a></li>
   <li><a href="morris_blog.php" <?php if ($currentPage == 'morris_blog.php') {echo 'id="here"'; } ?>>Logboek lezen</a></li>
   <li><a href="morris_blog_insert.php" <?php if ($currentPage == 'morris_blog_insert.php') {echo 'id="here"'; } ?>>Logboek aanvullen</a></li>
   <li><a href="morris_gallery.php" <?php if ($currentPage == 'morris_gallery.php') {echo 'id="here"'; } ?>>Fotogalerij</a></li>
   <li><a href="contact_us.php" <?php if ($currentPage == 'contact_us.php') {echo 'id="here"'; } ?>>Contact</a></li>
  </ul>
</nav>