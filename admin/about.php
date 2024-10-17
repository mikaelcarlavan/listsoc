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
 *  \file       htdocs/listsoc/index.php
 *  \ingroup    listsoc
 *  \brief      Page to show product set
 */


$res=@include("../../main.inc.php");                   // For root directory
if (! $res) $res=@include("../../../main.inc.php");    // For "custom" directory


// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';

dol_include_once("/listsoc/lib/listsoc.lib.php");

// Translations
$langs->load("listsoc@listsoc");

// Translations
$langs->load("errors");
$langs->load("admin");
$langs->load("other");

// Access control
if (! $user->admin) {
    accessforbidden();
}

$versions = array(
    array('version' => '1.0.0', 'date' => '17/10/2024', 'updates' => $langs->trans('ListSocFirstVersion')),
);


/*
 * View
 */

$form = new Form($db);

llxHeader('', $langs->trans('ListSocAbout'));

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">'. $langs->trans("BackToModuleList") . '</a>';
print load_fiche_titre($langs->trans('ListSocAbout'), $linkback);

// Configuration header
$head = listsoc_prepare_admin_head();
dol_fiche_head(
    $head,
    'about',
    $langs->trans("ModuleListSocName"),
    0,
    'listsoc@listsoc'
);

// About page goes here
echo $langs->trans("ListSocAboutPage");

echo '<br />';

print '<h2>'.$langs->trans("About").'</h2>';
print $langs->trans("ListSocAboutDescLong");

print '<hr />';
print '<h2>'.$langs->trans("ListSocChangeLog").'</h2>';


print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("ListSocChangeLogVersion").'</td>';
print '<td>'.$langs->trans("ListSocChangeLogDate").'</td>';
print '<td>'.$langs->trans("ListSocChangeLogUpdates").'</td>';
print "</tr>\n";

foreach ($versions as $version)
{
    print '<tr class="oddeven">';
    print '<td>';
    print $version['version'];
    print '</td>';
    print '<td>';
    print $version['date'];
    print '</td>';
    print '<td>';
    print $version['updates'];
    print '</td>';
    print '</tr>';
}


print '</table>';

// Page end
dol_fiche_end();
llxFooter();
$db->close();
