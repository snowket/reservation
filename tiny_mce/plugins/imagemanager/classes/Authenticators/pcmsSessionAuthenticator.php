<?php
/**
 * $Id: SessionAuthenticator.php 642 2009-01-19 13:49:06Z spocke $
 *
 * @package SessionAuthenticator
 * @author Moxiecode
 * @copyright Copyright © 2007, Moxiecode Systems AB, All rights reserved.
 */

@session_start();



/**
 * This class handles MCImageManager SessionAuthenticator stuff.
 *
 * @package SessionAuthenticator
 */
class Moxiecode_pcmsSessionAuthenticator extends Moxiecode_ManagerPlugin {
	/**#@+
	 * @access public
	 */

	/**
	 * SessionAuthenciator contructor.
	 */
	function pcmsSessionAuthenticator() {
	}

	/**
	 * Gets called on a authenication request. This method should check sessions or simmilar to
	 * verify that the user has access to the backend.
	 *
	 * This method should return true if the current request is authenicated or false if it's not.
	 *
	 * @param ManagerEngine $man ManagerEngine reference that the plugin is assigned to.
	 * @return bool true/false if the user is authenticated.
	 */
	 
	function onAuthenticate(&$man) {
        return isset($_SESSION['pcms_user_id']); 
	}
}

// Add plugin to MCManager
$man->registerPlugin("pcmsSessionAuthenticator", new Moxiecode_pcmsSessionAuthenticator());
?>