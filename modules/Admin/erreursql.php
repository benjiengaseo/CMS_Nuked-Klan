<?php
/**
 * erreursql.php
 *
 * Backend of Admin module
 *
 * @version     1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2015 Nuked-Klan (Registred Trademark)
 */
defined('INDEX_CHECK') or die('You can\'t run this file alone.');

if (! adminInit('Admin', ADMINISTRATOR_ACCESS))
    return;


function main()
{
    global $user, $nuked, $language;

    echo"<script type=\"text/javascript\">\n"
    ."<!--\n"
    ."\n"
    . "function delfile()\n"
    . "{\n"
    . "if (confirm('" . _DELETEFILE . " " . _VIDERSQL . " ! " . _CONFIRM . "'))\n"
    . "{document.location.href = 'index.php?file=Admin&page=erreursql&op=delete';}\n"
    . "}\n"
    . "\n"
    . "// -->\n"
    . "</script>\n";

    echo "<div class=\"content-box\">\n" //<!-- Start Content Box -->
    . "<div class=\"content-box-header\"><h3>" . _ADMINSQLERROR . "</h3>\n"
    . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Erreursql.php\" rel=\"modal\">\n"
    . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
    . "</div></div>\n"
    . "<div class=\"tab-content\" id=\"tab2\">\n";

    nkAdminMenu();

    echo "<table><tr><td><b>" . _DATE . "</b>\n"
    ."</td><td><b>" . _URL . "</b>\n"
    ."</td><td><b>" . _INFORMATION . "</b>\n"
    ."</td></tr>\n";

    $sql = mysql_query("SELECT date, lien, texte  FROM " . $nuked['prefix'] . "_erreursql ORDER BY date DESC");
    while (list($date, $lien, $texte) = mysql_fetch_array($sql))
    {
        $date = nkDate($date);

        echo "<tr><td>" . $date . "</td>\n"
        . "<td><a href=\"" . $nuked['url'] . $lien . "\">" . $lien . "</a></td>\n"
        . "<td>" . $texte . "</td></tr>\n";
    }
    echo "</table><div style=\"text-align: center;\"><br /><a class=\"buttonLink\" href=\"index.php?file=Admin\">" . __('BACK') . "</a></div></form><br /></div></div>\n";
}

function delete()
{
    global $user, $nuked, $visiteur;

    if ($visiteur == '9')
        $sql3 = mysql_query("DELETE FROM ". $nuked['prefix'] ."_erreursql");

    saveUserAction(_ACTIONVIDERSQL);

    printNotification(_SQLERRORDELETED, 'success');
    redirect('index.php?file=Admin&page=erreursql', 1);
}

function nkAdminMenu()
{
    global $language, $user, $nuked;
?>
    <div class= "nkAdminMenu">
        <ul class="shortcut-buttons-set" id="1">
            <li>
                <a class="shortcut-button" href="javascript:delfile();">
                    <img src="modules/Admin/images/icons/remove_from_database.png" alt="icon" />
                    <span><?php echo _VIDERSQL; ?></span>
                </a>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
<?php
}


switch ($_REQUEST['op']) {
    case 'main':
        main();
        break;
    case 'delete':
        delete();
        break;
    default:
        main();
        break;
}

?>