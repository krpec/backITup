<?php
	function __autoload($className) {
		require(strtolower("./include/$className.php"));
	}
	
	if(isset($_GET['appId']) && is_numeric($_GET['appId'])) {
		$appId = $_GET['appId'];
	}
	else {
		header("Location: ./index.php");
	}
	
	Html::header();
	
	$system = new System(dirname(__FILE__));
	$app = new Application($system);
	$app->load($appId);
?>
<section id="form">
        <h2>Přidat aplikaci</h2>
        <form method="post" action="./processForm.php">
			<input type="hidden" name="appId" value="<?php echo $appId ?>" />
            <table>
                <tr>
                    <th>
                        <label for="input-app-name">Název aplikace:</label>
                    </th>
                    <td>
                        <input type="text" name="name" id="input-app-name" value="<?php echo $app->name ?>" />
                    </td>
                </tr>
                <tr><th></th><td></td></tr>
                <tr>
                    <th>
                        <label for="input-ftp-backup">Zálohovat FTP:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="ftpBackup" <?php echo ($app->ftpBackup == 1? "checked=\"checked\"" : "") ?> id="input-ftp-backup" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-ftp-server">Adresa FTP serveru:</label>
                    </th>
                    <td>
                        <input type="text" name="ftpServer" id="input-ftp-server" value="<?php echo $app->ftp['server'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-ftp-user">Přihlašovací jméno k FTP:</label>
                    </th>
                    <td>
                        <input type="text" name="ftpUser" id="input-ftp-user" value="<?php echo $app->ftp['user'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-ftp-password">Heslo k FTP:</label>
                    </th>
                    <td>
                        <input type="text" name="ftpPassword" id="input-ftp-password" value="<?php echo $app->ftp['password'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-ftp-dir">Kořenový adresář:</label>
                    </th>
                    <td>
                        <input type="text" name="ftpDir" id="input-ftp-dir" value="<?php echo $app->ftp['defaultDirectory'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="select-ftp-method">Metoda FTP zálohy:</label>
                    </th>
                    <td>
                        <select name="ftpMethod" size="1" id="select-ftp-method">
                            <option value="normal" <?php echo ($app->ftp['method'] == "normal" ? "selected=\"selected\"" : "") ?>>Normální</option>
                            <option value="gzip" <?php echo ($app->ftp['method'] == "gzip" ? "selected=\"selected\"" : "") ?>>Archív gzip</option>
                            <!-- <option value="diff">Rozdílová</option>
                            <option value="gzdiff">Rozdílová gzip</option> -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-sql-backup">Zálohovat DB:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="dbBackup" <?php echo ($app->dbBackup == 1? "checked=\"checked\"" : "") ?> id="input-sql-backup" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-db-server">Adresa DB serveru:</label>
                    </th>
                    <td>
                        <input type="text" name="dbServer" id="input-db-server" value="<?php echo $app->db['server'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-db-user">Přihlašovací jméno k DB:</label>
                    </th>
                    <td>
                        <input type="text" name="dbUser" id="input-db-user" value="<?php echo $app->db['user'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-db-password">Heslo k DB:</label>
                    </th>
                    <td>
                        <input type="text" name="dbPassword" id="input-db-password" value="<?php echo $app->db['password'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-db-daabase">Název databáze:</label>
                    </th>
                    <td>
                        <input type="text" name="dbDatabase" id="input-db-database" value="<?php echo $app->db['database'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="select-db-method">Metoda DB zálohy:</label>
                    </th>
                    <td>
                        <select name="dbMethod" size="1" id="select-db-method">
                            <option value="normal" <?php echo ($app->db['method'] == "normal" ? "selected=\"selected\"" : "") ?>>Normální</option>
                            <option value="gzip" <?php echo ($app->db['method'] == "gzip" ? "selected=\"selected\"" : "") ?>>Archív gzip</option>
                            <option value="bz2" <?php echo ($app->db['method'] == "bz2" ? "selected=\"selected\"" : "") ?>>Archív bz2</option>
                            <!-- <option value="diff">Rozdílová</option>
                            <option value="gzdiff">Rozdílová gzip</option> -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-date-limit">Časový limit zálohy:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="dateLimit" id="input-date-limit" <?php echo ($app->dateLimit == 1 ? "checked=\"checked\"" : "") ?> />
                    </td>
                </tr>
                <tr>
                </tr>
                <tr>
                    <th>
                        <label for="input-backup-from">Zálohovat od:</label><span class="x-small">(YYYY-mm-dd)</span>
                    </th>
                    <td>
                        <input type="date" name="backupFrom" id="input-backup-from" value="<?php echo ($app->dates['from'] != "0000-00-00" ? $app->dates['from'] : "") ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-backup-to">Zálohovat do:</label><span class="x-small">(YYYY-mm-dd)</span>
                    </th>
                    <td>
                        <input type="date" name="backupTo" id="input-backup-to" value="<?php echo ($app->dates['to'] != "0000-00-00" ? $app->dates['to'] : "") ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="select-frequency">Frekvence záloh:</label>
                    </th>
                    <td>
                        <select name="frequency" id="select-frequency" size="1">
                            <option value="m" <?php echo ($app->frequency == "m" ? "selected=\"selected\"" : "") ?>>Mesíčně</option>
                            <option value="w" <?php echo ($app->frequency == "w" ? "selected=\"selected\"" : "") ?>>Týdně</option>
                            <option value="d" <?php echo ($app->frequency == "d" ? "selected=\"selected\"" : "") ?>>Denně</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="select-weekday">Den v týdnu:</label><span class="x-small">(Týdenní zálohy)</span>
                    </th>
                    <td>
                        <select name="weekday" size="1" id="select-weekday">
                            <option value="1" <?php echo ($app->weekday == 1 ? "selected=\"selected\"" : "") ?>>Pondělí</option>
                            <option value="2" <?php echo ($app->weekday == 2 ? "selected=\"selected\"" : "") ?>>Úterý</option>
                            <option value="3" <?php echo ($app->weekday == 3 ? "selected=\"selected\"" : "") ?>>Sředa</option>
                            <option value="4" <?php echo ($app->weekday == 4 ? "selected=\"selected\"" : "") ?>>Čtvrtek</option>
                            <option value="5" <?php echo ($app->weekday == 5 ? "selected=\"selected\"" : "") ?>>Pátek</option>
                            <option value="6" <?php echo ($app->weekday == 6 ? "selected=\"selected\"" : "") ?>>Sobota</option>
                            <option value="0" <?php echo ($app->weekday == 0 ? "selected=\"selected\"" : "") ?>>Neděle</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-time">Čas zálohy:</label>
                    </th>
                    <td>
                        <input type="time" name="time" id="input-time" value="<?php echo $app->backupTime ?>"/>
                    </td>
                </tr>
                <tr>
                    <th></tr>
                    <td>
                        <input type="submit" name="submit" value="Uložit" />
                    </td>
                </tr>
            </table>
        </form>
    </section>
<?php
	Html::footer();
?>