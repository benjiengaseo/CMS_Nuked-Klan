<?php

$dbTable->setTable($this->_session['db_prefix'] .'_sections');

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table function
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
 * Callback function for update row of sections database table
 */
function updateSectionsRow($updateList, $row, $vars) {
    $setFields = array();

    if (in_array('APPLY_BBCODE', $updateList))
        $setFields['content'] = $vars['bbcode']->apply(stripslashes($row['content']));

    return $setFields;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Check table integrity
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'checkIntegrity') {
    // table and field exist in 1.6.x version
    $dbTable->checkIntegrity('artid', 'content');
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Convert charset and collation
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'checkAndConvertCharsetAndCollation')
    $dbTable->checkAndConvertCharsetAndCollation();

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table creation
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'install') {
    $sql = 'CREATE TABLE `'. $this->_session['db_prefix'] .'_sections` (
        `artid` int(11) NOT NULL auto_increment,
        `secid` int(11) NOT NULL default \'0\',
        `title` text NOT NULL,
        `content` text NOT NULL,
        `autor` text NOT NULL,
        `autor_id` varchar(20) NOT NULL default \'\',
        `counter` int(11) NOT NULL default \'0\',
        `bbcodeoff` int(1) NOT NULL default \'0\',
        `smileyoff` int(1) NOT NULL default \'0\',
        `date` varchar(12) NOT NULL default \'\',
        PRIMARY KEY  (`artid`),
        KEY `secid` (`secid`)
        ) ENGINE=MyISAM DEFAULT CHARSET='. db::CHARSET .' COLLATE='. db::COLLATION .';';

    $dbTable->dropTable()->createTable($sql);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table update
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'update') {
    // Update BBcode
    // update 1.7.9 RC1
    if (version_compare($this->_session['version'], '1.7.9', '<=')) {
        $dbTable->setCallbackFunctionVars(array('bbcode' => new bbcode($this->_db, $this->_session, $this->_i18n)))
            ->setUpdateFieldData('APPLY_BBCODE', 'content');
    }

    $dbTable->applyUpdateFieldListToData('artid', 'updateSectionsRow');
}

?>