<?php
	class Html {
		public static function header() {
			echo <<<EOM
<!DOCTYPE html>
<html>
<head>
    <title>backITup</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="./css/style.css" media="all" />
</head>
<body>
    <header>
        <h1><a href="./index.php">backITup</a></h1>
        <span id="version">
            0.1 alpha
        </span>
    </header>
EOM;
		}
		
		public static function footer() {
echo <<<EOM
<footer>
        Created for job admission - KDV czech v.o.s., contact author: dittrich.r(at)gmail.com
    </footer>
</body>
</html>
EOM;
		}
	}