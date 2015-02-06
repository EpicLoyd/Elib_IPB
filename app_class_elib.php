<?php
if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

class app_class_elib
{
	/**#@+
	 * Registry Object Shortcuts
	 *
	 * @var		object
	 */
	protected $registry;
	protected $DB;
	protected $settings;
	protected $request;
	protected $lang;
	/**#@-*/
	public function __construct( ipsRegistry $registry )
	{
		$this->registry   = $registry;
		$this->DB         = $this->registry->DB();
		$this->settings   =& $this->registry->fetchSettings();
		$this->cache      =  $this->registry->cache();
		$this->caches     =& $this->registry->cache()->fetchCaches();
		$this->request    =& $this->registry->fetchRequest();
		$this->member     = $this->registry->member();
		$this->memberData =& $this->registry->member()->fetchMemberData();
		$this->lang       =  $this->registry->class_localization;
		

		require_once( IPSLib::getAppDir( 'elib' ) . "/sources/classes/elib_class.php" );

		if ( !ipsRegistry::isClassLoaded('elib_auth') )
        {
			//require_once( IPSLib::getAppDir( 'elib' ) . "/sources/classes/elib_class.php" );
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir('elib').'/sources/classes/elib_auth.php', 'public_elib_auth', 'elib' );
            $this->registry->setClass( 'elib_auth', new $classToLoad( $registry ) );
        }
		if (!ipsRegistry::isClassLoaded('elib_bill')){
			//$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir('elib').'/sources/classes/elib_bill.php', 'class_elib_', 'elib' );
            //$this->registry->setClass( 'elib_bill', new $classToLoad( $registry ) );
		}
		if (!ipsRegistry::isClassLoaded('elib_mcskin')){
			//$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir('elib').'/sources/classes/elib_mcskin.php', 'public_elib_mcskin', 'elib' );
            //$this->registry->setClass( 'elib_mcskin', new $classToLoad( $registry ) );
		}
			
}
}