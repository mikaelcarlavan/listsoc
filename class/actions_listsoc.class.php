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
 *  \file       htdocs/listsoc/class/actions_listsoc.class.php
 *  \ingroup    listsoc
 *  \brief      File of class to manage actions on propal
 */
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/notify.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/user/class/usergroup.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';

class ActionsListSoc
{
    function restrictedArea($parameters, &$object, &$action, $hookmanager)
    {
        global $langs, $db, $mysoc, $conf, $user;

        $features = $parameters['features'] ?? '';
        $objectid = $parameters['objectid'] ?? 0;

        $groupId = $conf->global->LISTSOC_APPLY_ON_GROUP_ID ?? 0;

        if ($features == 'societe' && !$user->admin) {
            $usergroup = new UserGroup($db);
            $groups = $usergroup->listGroupsForUser($user->id, false);
            $groups = is_array($groups) ? array_keys($groups) : array();

            if (in_array($groupId, $groups)) {
                $project = new Project($db);
                $objectsListId = $project->getProjectsAuthorizedForUser($user, 0, 0);

                $sql = "SELECT fk_soc FROM ".MAIN_DB_PREFIX."projet WHERE rowid IN (".implode(',', array_keys($objectsListId)).") AND fk_soc = ".intval($objectid);
                $resql = $db->query($sql);
                if ($resql) {
                    $num = $db->num_rows($resql);
                    $this->results = array('result' => $num);
                }
            }
        }

        return 0;
    }

    function printFieldListWhere($parameters, &$object, &$action, $hookmanager)
    {
        global $langs, $db, $mysoc, $conf, $user;

        $langs->load('listsoc@listsoc');

        $sql = '';


        $groupId = $conf->global->LISTSOC_APPLY_ON_GROUP_ID ?? 0;

        if ($groupId > 0) {
            $usergroup = new UserGroup($db);
            $groups = $usergroup->listGroupsForUser($user->id, false);
            $groups = is_array($groups) ? array_keys($groups) : array();

            if (in_array($groupId, $groups)) {
                $project = new Project($db);
                $objectsListId = $project->getProjectsAuthorizedForUser($user, 0, 0);

                $sql .= ' AND s.rowid IN (SELECT DISTINCT pr.fk_soc FROM '.MAIN_DB_PREFIX."projet AS pr WHERE pr.rowid IN (".implode(',', array_keys($objectsListId))."))";
            }
        }

        $this->resprints = $sql;
        return 0;
    }

}


