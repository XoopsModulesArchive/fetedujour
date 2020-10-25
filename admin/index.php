<?php

// ------------------------------------------------------------------------- //
// XOOPS - PHP Content Management System //
// <http://xoops.codigolivre.org.br> //
// ------------------------------------------------------------------------- //
// Based on:   //
// myPHPNUKE Web Portal System - http://myphpnuke.com/ //
// PHP-NUKE Web Portal System - http://phpnuke.org/ //
// Thatware - http://thatware.org/  //
// ------------------------------------------------------------------------- //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version.  //
//   //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
//   //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------- //
$op = $_POST['op'] ?? $_GET['op'] ?? 'main';
require_once 'admin_header.php';
xoops_cp_header();
$myts = MyTextSanitizer::getInstance();
echo '<br>';
switch ($op) {
case 'edit':
$jour     = $_POST['jour'] ?? $_GET['jour'] ?? 'main';
    $mois = $_POST['mois'] ?? $_GET['mois'] ?? 'main';
fetedujour_admin_edit($jour, $mois);
break;
case 'sort':
$order = $_POST['order'] ?? $_GET['order'] ?? 'mois';
fetedujour_admin_add();
fetedujour_admin_browse_sort($order);
break;
case 'editdb':
$form_jour     = $_POST['form_jour'] ?? $_GET['form_jour'] ?? 'main';
    $form_mois = $_POST['form_mois'] ?? $_GET['form_mois'] ?? 'main';
    $form_fete = $_POST['form_fete'] ?? $_GET['form_fete'] ?? 'main';
$q             = 'UPDATE ' . $xoopsDB->prefix('fetes') . " SET fete='" . $form_fete . "' WHERE mois='" . $form_mois . "' AND jour='" . $form_jour . "'";
if ($xoopsDB->query($q)) {
    redirect_header('index.php', 1, _AD_FETEDUJOUR_EDIT_DONE);
} else {
    redirect_header('index.php', 1, _AD_FETEDUJOUR_EDIT_DBERROR);
}
fetedujour_admin_add();
fetedujour_admin_browse();
break;
case 'del':
$jour     = $_POST['jour'] ?? $_GET['jour'] ?? 'main';
    $mois = $_POST['mois'] ?? $_GET['mois'] ?? 'main';
    $fete = $_POST['fete'] ?? $_GET['fete'] ?? 'main';
if ($xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('fetes') . " WHERE mois='" . $mois . "' AND jour='" . $jour . "'")) {
    redirect_header('index.php', 1, _AD_FETEDUJOUR_DEL_OK);
} else {
    redirect_header('index.php', 1, _AD_FETEDUJOUR_EDIT_DBERROR);
}
fetedujour_admin_add();
fetedujour_admin_browse();
break;
case 'delconfirm':
$jour     = $_POST['jour'] ?? $_GET['jour'] ?? 'main';
    $mois = $_POST['mois'] ?? $_GET['mois'] ?? 'main';
    $fete = $_POST['fete'] ?? $_GET['fete'] ?? 'main';
OpenTable();
$result = $xoopsDB->queryF('SELECT * FROM ' . $xoopsDB->prefix('fetes') . " WHERE mois='" . $mois . "' AND jour='" . $jour . "'", 1);
$fetedujour_item = $xoopsDB->fetchArray($result);
echo '<center><h4>' . _AD_FETEDUJOUR_DEL_REALLY . '</h4></center>
<br>' . _AD_FETEDUJOUR_JOUR . ' : ' . htmlspecialchars($fetedujour_item['jour'], ENT_QUOTES | ENT_HTML5) . '
<br>' . _AD_FETEDUJOUR_MOIS . ' : ' . htmlspecialchars($fetedujour_item['mois'], ENT_QUOTES | ENT_HTML5) . '
<br>' . _AD_FETEDUJOUR_FETE . ' : ' . htmlspecialchars($fetedujour_item['fete'], ENT_QUOTES | ENT_HTML5) . "
<br><form action='index.php' method='post'>
<input type='hidden' name='jour' value='" . $jour . "'>
<input type='hidden' name='mois' value='" . $mois . "'>
<input type='hidden' name='op' value='del'>
<input type='submit' value='" . _AD_FETEDUJOUR_DELETE . "'>&nbsp;
<input type='button' value='" . _CANCEL . "' onclick='javascript:history.go(-1);'></form>";
CloseTable();
break;
case 'add':
$form_jour     = $_POST['form_jour'] ?? $_GET['form_jour'] ?? 'main';
    $form_mois = $_POST['form_mois'] ?? $_GET['form_mois'] ?? 'main';
    $form_fete = $_POST['form_fete'] ?? $_GET['form_fete'] ?? 'main';
$form_fete     = $myts->addSlashes($form_fete);
$q             = 'INSERT INTO ' . $xoopsDB->prefix('fetes') . " (jour, mois, fete) VALUES ('" . $form_jour . "', '" . $form_mois . "', '" . $form_fete . "')";
if ($xoopsDB->query($q)) {
    redirect_header('index.php', 1, _AD_FETEDUJOUR_EDIT_DONE);
} else {
    redirect_header('index.php', 1, _AD_FETEDUJOUR_EDIT_DBERROR);
}
break;
case 'main':
fetedujour_admin_add();
fetedujour_admin_browse();
break;
default:
fetedujour_admin_add();
fetedujour_admin_browse();
break;
}
xoops_cp_footer();
//*****************************************************************************************
//*** Functions-declaration ***************************************************************
//*****************************************************************************************
function fetedujour_admin_add()
{
    global $xoopsConfig;

    OpenTable();

    echo '<form name="Add Fete" action="./index.php" method="post">
<div align="center">
<h4>' . _AD_FETEDUJOUR_ADD_HEADER . '</h4>
</div>
<table border="0" cellpadding="2" cellspacing="2" width="95%">
<tr>
<td align="right">' . _AD_FETEDUJOUR_JOUR . ':</td>
<td>
<input type="text" name="form_jour" size="30" maxlength="50" tabindex="1"><br>
</td>
</tr>
<tr>
<td align="right">' . _AD_FETEDUJOUR_MOIS . ':</td>
<td>
<input type="text" name="form_mois" size="30" maxlength="50" tabindex="2"><br>
</td>
</tr>
<tr>
<td align="right">' . _AD_FETEDUJOUR_FETE . ':</td>
<td>
<input type="text" name="form_fete" size="100" maxlength="255" value="" tabindex="3">
</td>
</tr>
<tr height="10">
<td align="right" height="10"></td>
<td height="10">
<input type="hidden" value="add" name="op">
</td>
</tr>
<tr>
<td align="right"></td>
<td>
<input type="submit" name="add" tabindex="4" value="' . _AD_FETEDUJOUR_ADD_SUBMIT_ADD . '">
<input type="reset" tabindex="5" value="' . _AD_FETEDUJOUR_ADD_SUBMIT_RESET . '">
</td>
</tr>
</table>
</form>';

    CloseTable();
}
function fetedujour_admin_browse()
{
    $Mois = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    global $xoopsDB, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    OpenTable();

    // Make query if $in contains a valide value

    echo '<table border=2 cellpadding=2 cellspacing=2 width="95%">
<tr>
<th><a href="./index.php?op=sort&order=jour">' . _AD_FETEDUJOUR_JOUR . '</a></th>
<th><a href="./index.php?op=sort&order=mois">' . _AD_FETEDUJOUR_MOIS . '</a></th>
<th><a href="./index.php?op=sort&order=fete">' . _AD_FETEDUJOUR_FETE . '</a></th>
<th>' . _AD_FETEDUJOUR_ACTION . '</th>
</tr>';

    // get all selected rows from db

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('fetes') . '');

    while ($fetedujour_item = $xoopsDB->fetchArray($result)) {
        echo '<tr><td>' . htmlspecialchars($fetedujour_item['jour'], ENT_QUOTES | ENT_HTML5) . '</td>
<td>' . $Mois[$fetedujour_item['mois']] . '</td>
<td>' . htmlspecialchars($fetedujour_item['fete'], ENT_QUOTES | ENT_HTML5) . '</td>
<td><a href="./index.php?op=edit&jour=' . $fetedujour_item['jour'] . '&mois=' . $fetedujour_item['mois'] . '">' . _AD_FETEDUJOUR_EDIT . '</a>&nbsp;|&nbsp;
<a href="./index.php?op=delconfirm&jour=' . $fetedujour_item['jour'] . '&mois=' . $fetedujour_item['mois'] . '">' . _AD_FETEDUJOUR_DELETE . '</a></td></tr>';
    }

    echo '</table>';

    CloseTable();
}
function fetedujour_admin_browse_sort($order)
{
    $Mois = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    global $xoopsDB, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    OpenTable();

    // Make query if $in contains a valide value

    echo '<table border=2 cellpadding=2 cellspacing=2 width="95%">
<tr>
<th><a href="./index.php?op=sort&order=jour">' . _AD_FETEDUJOUR_JOUR . '</a></th>
<th><a href="./index.php?op=sort&order=mois">' . _AD_FETEDUJOUR_MOIS . '</a></th>
<th><a href="./index.php?op=sort&order=fete">' . _AD_FETEDUJOUR_FETE . '</a></th>
<th>' . _AD_FETEDUJOUR_ACTION . '</th>
</tr>';

    // get all selected rows from db

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('fetes') . ' ORDER BY ' . $order . '');

    while ($fetedujour_item = $xoopsDB->fetchArray($result)) {
        echo '<tr><td>' . htmlspecialchars($fetedujour_item['jour'], ENT_QUOTES | ENT_HTML5) . '</td>
<td>' . $Mois[$fetedujour_item['mois']] . '</td>
<td>' . htmlspecialchars($fetedujour_item['fete'], ENT_QUOTES | ENT_HTML5) . '</td>
<td><a href="./index.php?op=edit&jour=' . $fetedujour_item['jour'] . '&mois=' . $fetedujour_item['mois'] . '">' . _AD_FETEDUJOUR_EDIT . '</a>&nbsp;|&nbsp;
<a href="./index.php?op=delconfirm&jour=' . $fetedujour_item['jour'] . '&mois=' . $fetedujour_item['mois'] . '">' . _AD_FETEDUJOUR_DELETE . '</a></td></tr>';
    }

    echo '</table>';

    CloseTable();
}
function fetedujour_admin_edit($jour, $mois)
{
    global $xoopsConfig, $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('fetes') . " WHERE mois='" . $mois . "' AND jour='" . $jour . "'");

    $fetedujour_item = $xoopsDB->fetchArray($result);

    OpenTable();

    echo '<form name="Edit Content" action="./index.php" method="post">
<div align="center"><h4>' . _AD_FETEDUJOUR_EDIT_THISFETE . '</h4></div>
<table border="0" cellpadding="2" cellspacing="2" width="95%">
<tr>
<td align="right">' . _AD_FETEDUJOUR_JOUR . ':</td>
<td><input type="text" value="' . $jour . '" name="form_jour" size="5" readonly></td>
</tr>
<tr>
<td align="right">' . _AD_FETEDUJOUR_MOIS . ':</td>
<td><input type="text" value="' . $mois . '" name="form_mois" size="5" readonly></td>
</tr>
<tr>
<td align="right">' . _AD_FETEDUJOUR_FETE . ':</td>
<td><input type="text" value="' . htmlspecialchars($fetedujour_item['fete'], ENT_QUOTES | ENT_HTML5) . '" name="form_fete" size="100" maxlength="255" value="" tabindex="2"></td>
</tr> 
<tr height="10">
<td align="right" height="10"></td>
<td height="10"><input type="hidden" value="editdb" name="op"></td>
</tr>
<tr>
<td align="right"></td>
<td>
<input type="submit" name="editdb" tabindex="3" value="' . _AD_FETEDUJOUR_SUBMIT_UPD . "\">
<input type='button' value='" . _CANCEL . "' onclick='javascript:history.go(-1);'>
</td>
</tr>
</table>
</form>";

    CloseTable();
}
