<?php
/* Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2017 Mikael Carlavan <contact@mika-carl.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       htdocs/listsoc/admin/setup.php
 *  \ingroup    listsoc
 *  \brief      Admin page
 */


$res=@include("../../main.inc.php");                   // For root directory
if (! $res) $res=@include("../../../main.inc.php");    // For "custom" directory

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once DOL_DOCUMENT_ROOT ."/core/class/html.formmail.class.php";
require_once DOL_DOCUMENT_ROOT . '/user/class/usergroup.class.php';

dol_include_once("/listsoc/lib/listsoc.lib.php");

// Translations
$langs->load("listsoc@listsoc");
$langs->load("admin");

// Access control
if (! $user->admin) accessforbidden();

// Parameters
$action = GETPOST('action', 'alpha');
$value = GETPOST('value', 'alpha');

$reg = array();

/*
 * Actions
 */

$usergroups = array();

$sql = "SELECT g.rowid, g.nom as name";
$sql .= " FROM ".MAIN_DB_PREFIX."usergroup as g";
$sql .= " WHERE g.entity IN (0,".$conf->entity.")";

$result = $db->query($sql);
if ($result) {
    $num = $db->num_rows($result);
    if ($num) {
        $i = 0;
        while ($i < $num) {
            $obj = $db->fetch_object($result);

            $group = new UserGroup($db);
            $group->id = $obj->rowid;
            $group->name = $obj->name;

            $usergroups[$group->id] = $group->name;

            $i++;
        }
    }
}

// include DOL_DOCUMENT_ROOT.'/core/actions_setmoduleoptions.inc.php';

$error=0;

// Action mise a jour ou ajout d'une constante
if ($action == 'update')
{
    $constname=GETPOST('constname','alpha');
    $constvalue=(GETPOST('constvalue_'.$constname) ? GETPOST('constvalue_'.$constname) : GETPOST('constvalue'));


    $consttype=GETPOST('consttype','alpha');
    $constnote=GETPOST('constnote', 'alpha');
    $res = dolibarr_set_const($db,$constname,$constvalue,'chaine',0,$constnote,$conf->entity);

    if (! $res > 0) $error++;

    if (! $error)
    {
        setEventMessages($langs->trans("SetupSaved"), null, 'mesgs');
    }
    else
    {
        setEventMessages($langs->trans("Error"), null, 'errors');
    }
}

/*
 * View
 */

llxHeader('', $langs->trans('ListSocSetup'));

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">' . $langs->trans("BackToModuleList") . '</a>';

print load_fiche_titre($langs->trans('ListSocSetup'), $linkback);

// Configuration header
$head = listsoc_prepare_admin_head();
dol_fiche_head(
    $head,
    'settings',
    $langs->trans("ModuleListSocName"),
    0,
    "listsoc@listsoc"
);

$form = new Form($db);


print load_fiche_titre($langs->trans("ListSocOptions"),'','');

print '<table class="noborder" width="100%">';
print '<tbody>';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Name").'</td>';
print '<td align="left">'.$langs->trans("Action").'</td>';
print "</tr>\n";

print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">';
print '<tr class="oddeven">';
print '<td>';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="update">';
print '<input type="hidden" name="constname" value="LISTSOC_APPLY_ON_GROUP_ID">';
print '<input type="hidden" name="constnote" value="">';
print $langs->trans('DescLISTSOC_APPLY_ON_GROUP_ID');
print '</td>';
print '<td>';
print $form->selectarray('constvalue', $usergroups, (isset($conf->global->LISTSOC_APPLY_ON_GROUP_ID) ? $conf->global->LISTSOC_APPLY_ON_GROUP_ID : ''), 1, 0, 0, '', 0, 0, 0, '', 'minwidth75', 0);
print '<input type="hidden" name="consttype" value="chaine">';
print '</td>';
print '<td align="center">';
print '<input type="submit" class="button" value="'.$langs->trans("Update").'" name="Button">';
print '</td>';
print '</tr>';
print '</form>';


print '</tbody>';
print '</table>';

// Page end
dol_fiche_end();
llxFooter();
