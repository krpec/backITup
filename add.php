<?php
	function __autoload($className) {
		require(strtolower("./include/$className.php"));
	}
	
	Html::header();
?>
<section id="form">
        <h2>Přidat aplikaci</h2>
        <form method="post" action="./processForm.php">
            <table>
                <tr>
                    <th>
                        <label for="input-app-name">Název aplikace:</label>
                    </th>
                    <td>
                        <input type="text" name="name" id="input-app-name" />
                    </td>
                </tr>
                <tr><th></th><td></td></tr>
                <tr>
                    <th>
                        <label for="input-ftp-backup">Zálohovat FTP:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="ftpBackup" checked="checked" id="input-ftp-backup" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-ftp-server">Adresa FTP serveru:</label>
                    </th>
                    <td>
                        <input type="text" name="ftpServer" id="input-ftp-server" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-ftp-user">Přihlašovací jméno k FTP:</label>
                    </th>
                    <td>
                        <input type="text" name="ftpUser" id="input-ftp-user" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-ftp-password">Heslo k FTP:</label>
                    </th>
                    <td>
                        <input type="text" name="ftpPassword" id="input-ftp-password" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-ftp-dir">Kořenový adresář:</label>
                    </th>
                    <td>
                        <input type="text" name="ftpDir" id="input-ftp-dir" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="select-ftp-method">Metoda FTP zálohy:</label>
                    </th>
                    <td>
                        <select name="ftpMethod" size="1" id="select-ftp-method">
                            <option value="normal">Normální</option>
                            <option value="gzip">Archív gzip</option>
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
                        <input type="checkbox" name="dbBackup" checked="checked" id="input-sql-backup" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-db-server">Adresa DB serveru:</label>
                    </th>
                    <td>
                        <input type="text" name="dbServer" id="input-db-server" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-db-user">Přihlašovací jméno k DB:</label>
                    </th>
                    <td>
                        <input type="text" name="dbUser" id="input-db-user" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-db-password">Heslo k DB:</label>
                    </th>
                    <td>
                        <input type="text" name="dbPassword" id="input-db-password" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-db-daabase">Název databáze:</label>
                    </th>
                    <td>
                        <input type="text" name="dbDatabase" id="input-db-database" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="select-db-method">Metoda DB zálohy:</label>
                    </th>
                    <td>
                        <select name="dbMethod" size="1" id="select-db-method">
                            <option value="normal">Normální</option>
                            <option value="gzip">Archív gzip</option>
                            <option value="bz2">Archív bz2</option>
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
                        <input type="checkbox" name="dateLimit" id="input-date-limit" />
                    </td>
                </tr>
                <tr>
                    
                </tr>
                <tr>
                    <th>
                        <label for="input-backup-from">Zálohovat od:</label><span class="x-small">(YYYY-mm-dd)</span>
                    </th>
                    <td>
                        <input type="date" name="backupFrom" id="input-backup-from" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-backup-to">Zálohovat do:</label><span class="x-small">(YYYY-mm-dd)</span>
                    </th>
                    <td>
                        <input type="date" name="backupTo" id="input-backup-to" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="select-frequency">Frekvence záloh:</label>
                    </th>
                    <td>
                        <select name="frequency" id="select-frequency" size="1">
                            <option value="m">Mesíčně</option>
                            <option value="w">Týdně</option>
                            <option value="d">Denně</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="select-weekday">Den v týdnu:</label><span class="x-small">(Týdenní zálohy)</span>
                    </th>
                    <td>
                        <select name="weekday" size="1" id="select-weekday">
                            <option value="1">Pondělí</option>
                            <option value="2">Úterý</option>
                            <option value="3">Sředa</option>
                            <option value="4">Čtvrtek</option>
                            <option value="5">Pátek</option>
                            <option value="6">Sobota</option>
                            <option value="0">Neděle</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="input-time">Čas zálohy:</label>
                    </th>
                    <td>
                        <input type="time" name="time" id="input-time" />
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