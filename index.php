<?php
/**
 * index.php
 *
 * Index of CMS Nuked-Klan
 *
 * @version 1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2015 Nuked-Klan (Registred Trademark)
 */

// Permet de s'assurer que tous les scripts passe bien par l'index du CMS
define('INDEX_CHECK', 1);
ini_set('default_charset', 'ISO8859-1');

require_once 'Includes/fatal_errors.php';
require_once 'globals.php';

if (file_exists('conf.inc.php'))
    require_once 'conf.inc.php';

require_once 'nuked.php';


/**
 * Checks if site is closed or not installed
 */
nkHandle_siteInstalled();

if ($nuked['time_generate'] == 'on')
    $microTime = microtime(true);

// GESTION DES ERREURS SQL - SQL ERROR MANAGEMENT
if(ini_get('set_error_handler')) set_error_handler('erreursql');


if ($nuked['stats_share'] == 1 && isset($_GET['ajax']) && $_GET['ajax'] == 'sendNkStats') {
    require_once 'Includes/nkStats.php';

    nkStats_send();
}


/**
 * Get user ( $user var is affected to $GLOBALS['user'] )
 */
nkSessions_getUser();


/**
 * Checks if current user is banned or not
 */
nkHandle_bannedUser();


/**
 * Choose which module file will be include
 */
$GLOBALS['page'] = nkHandle_page();


// Hack for CSRF vulnerabilities
if ($_SESSION['admin'] == true &&
    $GLOBALS['file'] != 'Admin'
    && $GLOBALS['page'] != 'admin'
    && (! ($GLOBALS['file'] == 'Textbox' && $GLOBALS['page'] == 'index' && $GLOBALS['op'] == 'ajax'))
) {
    $_SESSION['admin'] = false;
}


/**
 * Init translation
 */
translate('lang/'. $language .'.lang.php');


// If website is closed
if ($nuked['nk_status'] == 'closed'
    && $visiteur < 9
    && ! in_array($GLOBALS['op'], array('login_screen', 'login_message', 'login'))
) {
    nkTemplate_setBgColors();
    nkTemplate_init();
    echo nkTemplate_renderPage(applyTemplate('websiteClosed'));
}
// Display admin login
else if (($GLOBALS['file'] == 'Admin' || $GLOBALS['page'] == 'admin')
    && nkSessions_adminCheck() == false
) {
    require_once 'modules/Admin/login.php';
}
// Run module
else {
    ob_start();

    nkTemplate_setBgColors();
    require_once 'themes/'. $theme .'/theme.php';

    if ($nuked['level_analys'] != -1)
        visits();

    if (is_file('modules/'. $GLOBALS['file'] .'/'. $GLOBALS['page'] .'.php'))
        require_once 'modules/'. $GLOBALS['file'] .'/'. $GLOBALS['page'] .'.php';
    else
        require_once 'modules/404/index.php';

    $content = ob_get_clean();

    if (in_array(nkTemplate_getPageDesign(), array('fullPage', 'nudePage'))) {
        nkTemplate_init($GLOBALS['file']);
        $content = nkTemplate_renderPage($content);

        if (defined('NK_GZIP') && ini_get('zlib_output'))
            ob_start('ob_gzhandler');
    }
    else
        header('Content-Type: text/html;charset=ISO-8859-1');

    echo $content;
}

nkDB_disconnect();

/*
if (in_array(nkTemplate_getPageDesign(), array('fullPage', 'nudePage'))) {
    echo '<!--', "\n";
    print_r($GLOBALS['nkDB']['querys']);
    echo '-->', "\n";
}*/

?>