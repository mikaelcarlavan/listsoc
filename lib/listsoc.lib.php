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
 *	\file       htdocs/listsoc/lib/listsoc.lib.php
 *	\brief      Ensemble de fonctions de base pour le module listsoc
 * 	\ingroup	listsoc
 */
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/link.class.php';
/**
 * Prepare array with list of tabs
 *
 * @param   Product	$object		Object related to tabs
 * @return  array				Array of tabs to show
 */
function listsoc_prepare_admin_head()
{
	global $db, $langs, $conf, $user;
	$langs->load("listsoc@listsoc");

	$h = 0;
	$head = array();

    $head[$h][0] = dol_buildpath("/listsoc/admin/setup.php", 1);
    $head[$h][1] = $langs->trans("Settings");
    $head[$h][2] = 'settings';
    $h++;

    $head[$h][0] = dol_buildpath("/listsoc/admin/about.php", 1);
    $head[$h][1] = $langs->trans("About");
    $head[$h][2] = 'about';
    $h++;

	return $head;
}
