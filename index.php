<?php
	function __autoload($className) {
		require(strtolower("./include/$className.php"));
	}
	
	Html::header();
	
	$system = new System(dirname(__FILE__));
	$system->loadAllApplications();
?>
<section id="app-list">
        <h2>Seznam aplikací</h2>
        <span class="section-head-link">
            <a href="./add.php"><span class="icon-no-float icon-new"></span>Přidat zálohu</a>
        </span>
        <table class='datagrid'>
            <tr>
                <th>
                    Id
                </th>
                <th>
                    Název aplikace
                </th>
                <th>
                    FTP
                </th>
                <th>
                    MySQL
                </th>
                <th>
                    Akce
                </th>
            </tr>
<?php
	foreach($system->apps as $app) {
?>
			<tr>
                <td>
                    <?php echo $app->appId() ?>
                </td>
                <td>
                    <?php echo $app->name ?>
                </td>
                <td>
                    <a href="./switch.php?switch=ftp&appId=<?php echo $app->appId() ?>" title="Zapnout/vypnout zálohu FTP">
                        <span class="icon-no-float <?php echo ($app->ftpBackup ? "icon-green-flag" : "icon-red-flag") ?>"></span>
                    </a>
                </td>
                <td>
                    <a href="./switch.php?switch=sql&appId=<?php echo $app->appId() ?>" title="Zapnout/vypnout zálohu MySQL">
                        <span class="icon-no-float <?php echo ($app->dbBackup ? "icon-green-flag" : "icon-red-flag") ?>"></span>
                    </a>
                </td>
                <td>
                    <a href="./edit.php?appId=<?php echo $app->appId() ?>" title="Editovat">
                        <span class="icon icon-edit"></span>
                    </a>
                    <a href="./delete.php?appId=<?php echo $app->appId() ?>" title="Smazat">
                        <span class="icon icon-delete"></span>
                    </a>
					<a href="./backup.php?appId=<?php echo $app->appId() ?>&redirect=1" title="Provést zálohu">
						<span class="icon icon-disk"></span>
					</a>
                </td>
            </tr>
<?php
	} //end foreach
?>
            
        </table>
    </section>
	<section id="cron-list">
        <h2>Akce CRONu</h2>
        <span class="section-head-link">
            <a href="./cronSetup.php?redirect=1"><span class="icon-no-float icon-refresh"></span>Aktualizovat</a>
        </span>
		<form action="" method="POST">
			<textarea disabled="disabled">
<?php
	$fName = $system->baseDir."/".$system->tmp."/cronSetup";
	if(is_file($fName)) {
		$file = fopen($fName, "r");
		$cron = fread($file, filesize($fName));
	}
	else {
		$cron = "V adresáři není lokální kopie crontab, pro zobrazení jej zaktualizujte.";
	}
	
	echo $cron;
?>
			</textarea>
		</form
    </section>
<?php
	Html::footer();
?>