<footer>
  <p>&copy;
  <?php
  $startYear = 2020;
  $thisYear = date('Y');
  if ($startYear == $thisYear) {
  	echo $startYear;
  } else {
  	echo "{$startYear}&ndash;{$thisYear}";
  }
  ?> 
  <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
</footer>