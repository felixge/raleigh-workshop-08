;<?php die() ?>
; SVN FILE: $Id: acl.ini.php 7062 2008-05-30 11:29:53Z nate $
;/**
; * Test App Ini Based Acl Config File
; *
; *
; * PHP versions 4 and 5
; *
; * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
; * Copyright 2005-2008, Cake Software Foundation, Inc.
; *							1785 E. Sahara Avenue, Suite 490-204
; *							Las Vegas, Nevada 89104
; *
; *  Licensed under The MIT License
; *  Redistributions of files must retain the above copyright notice.
; *
; * @filesource
; * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
; * @link			http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
; * @package		cake
; * @subpackage	cake.app.config
; * @since			CakePHP(tm) v 0.10.0.1076
; * @version		$Revision: 7062 $
; * @modifiedby	$LastChangedBy: nate $
; * @lastmodified	$Date: 2008-05-30 13:29:53 +0200 (Fr, 30 Mai 2008) $
; * @license		http://www.opensource.org/licenses/mit-license.php The MIT License
; */

;-------------------------------------
;Users
;-------------------------------------

[admin]
groups = administrators
allow = 
deny = ads

[paul]
groups = users
allow = 
deny = 

[jenny]
groups = users
allow = ads
deny = images, files

[nobody]
groups = anonymous
allow = 
deny =

;-------------------------------------
;Groups
;-------------------------------------

[administrators]
deny =
allow = posts, comments, images, files, stats, ads

[users]
allow = posts, comments, images, files
deny = stats, ads

[anonymous]
allow = 
deny = posts, comments, images, files, stats, ads