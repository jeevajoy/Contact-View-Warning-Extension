<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.3                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2010                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2010
 * $Id$
 *
 */

//'batch details' DB Table Name
define( 'CIVICRM_NOTE_IS_WARNING' ,   'mtl_civicrm_note_is_warning' );

function civicrm_contact_view_warning_civicrm_config( &$config ) {
    $template =& CRM_Core_Smarty::singleton( );
    $warningRoot = dirname( __FILE__ );
    $warningDir = $warningRoot . DIRECTORY_SEPARATOR . 'templates';
    if ( is_array( $template->template_dir ) ) {
        array_unshift( $template->template_dir, $warningDir );
    } else {
        $template->template_dir = array( $warningDir, $template->template_dir );
    }
    // also fix php include path
    $include_path = $warningRoot . PATH_SEPARATOR . get_include_path( );
    set_include_path( $include_path );
}

/*
 * Implementation of hook_civicrm_tokens 
 */ 
function civicrm_contact_view_warning_civicrm_pageRun( &$page ) {
    $name = $page->getVar( '_name' );
    $_contactId = $page->getVar( '_contactId' ); 
    if ($name == 'CRM_Contact_Page_View_Summary' && !empty($_contactId)) {
        $query = "SELECT * FROM civicrm_note cn
                    INNER JOIN ".CIVICRM_NOTE_IS_WARNING." ciw ON ciw.entity_id = cn.id 
                    WHERE cn.entity_id = %1 AND cn.entity_table = %2
                    AND ciw.is_warning = 1
                    ";
        $dao = CRM_Core_DAO::executeQuery( $query , array( 
                                                            1 => array( $_contactId   , 'Int' ) ,
                                                            2 => array( 'civicrm_contact' , 'String' ) 
                                                            ) );
        if ($dao->fetch()) {
            $page->assign('warning' , $dao->note);
        }                                                                            
    }
}


function civicrm_contact_view_warning_civicrm_buildForm( $formName, &$form ){
    if ($formName = 'CRM_Note_Form_Note' && $form->getVar('_entityId') ) {
        $form->add('checkbox', 'is_warning', ts('Is Warning?'));
        
        $note_id = $form->getVar('_id');
        if ($note_id) {
            $sql = "SELECT * FROM ".CIVICRM_NOTE_IS_WARNING." WHERE entity_id = ".$note_id;
     		$dao = CRM_Core_DAO::executeQuery($sql);
            if ($dao->fetch()){
                $defaults['is_warning'] = $dao->is_warning;
                $form->setDefaults( $defaults );       
            } 
        }
    }    
}

function civicrm_contact_view_warning_civicrm_postProcess( $formName, &$form ){
    if ($formName = 'CRM_Note_Form_Note' && $form->getVar('_entityId') ) {
        
        require_once("CRM/Core/DAO/Note.php");
        $note	= new CRM_Core_DAO_Note();
        $whereStr = "note = '".$form->_submitValues['note']."' AND entity_table = 'civicrm_contact' AND entity_id = ".$form->getVar('_entityId');
        $note->whereAdd($whereStr);
        $note->find(true);
        
        $note_id = $note->id;
        
        if ($form->_submitValues['is_warning'])
		   $is_warning = 1;
	    else
           $is_warning = 0;	   
		
		$contact_id = $form->getVar('_entityId');
        $selectSql = "SELECT * FROM ".CIVICRM_NOTE_IS_WARNING." WHERE contact_id = ".$contact_id;
        $selectDao = CRM_Core_DAO::executeQuery($selectSql);
        if($selectDao->fetch()) {
            if ($is_warning == 0 OR $note_id != $selectDao->entity_id) {
                CRM_Core_DAO::executeQuery("DELETE FROM ".CIVICRM_NOTE_IS_WARNING." WHERE contact_id = ".$contact_id);
            }            
        }  

        if ($is_warning == 1) {
           if(!empty($note_id)) {
               $sql = "REPLACE INTO ".CIVICRM_NOTE_IS_WARNING." SET entity_id = ".$note_id." , contact_id = ".$contact_id." , is_warning = ".$is_warning;
    	       CRM_Core_DAO::executeQuery($sql);
           }
        }
    }    
}




