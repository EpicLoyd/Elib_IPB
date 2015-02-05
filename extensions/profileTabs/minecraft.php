<?php

class profile_minecraft extends profile_plugin_parent
{
	public function return_html_block($member=array())
	{
		$registry = ipsRegistry::instance();
		$registry->output->addJSModule( "minecraft_skin", 0 );
        if ( !ipsRegistry::isClassLoaded('elib_core') )
        {
			//require_once( IPSLib::getAppDir( 'elib' ) . "/sources/classes/elib_class.php" );
            $classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir('elib').'/sources/classes/elib_core.php', 'class_elib_core', 'elib' );
            $this->registry->setClass( 'elib_core', new $classToLoad( $registry ) );
        }
		if (!ipsRegistry::isClassLoaded('elib_mcskin')){
			$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir('elib').'/sources/classes/elib_mcskin.php', 'public_elib_mcskin', 'elib' );
            $this->registry->setClass( 'elib_mcskin', new $classToLoad( $registry ) );
		}
		$image = $this->registry->getClass('elib_mcskin')->GetImage();
		$content = " ";
	    $user = 'earthiverse';
		
//--starthtml--//
$content .= <<<EOF
{parse addtohead="minecraft_skin.js" type="javascript"}
<div class='row2 ipsPad'>
    {$this->lang->words['elib_text_hw']}
	<img src = "avatarka.jpg" alt = "TEST" style = "width:100px;height:100px">
	<img src="data:image/png;base64,{$image}"/>
</div>
<br />
<div class="minecraft_head">
    <canvas class="hat" id="hat"></canvas>
    <canvas class="head" id="head"></canvas>
    <script type="text/javascript">
        draw_hat('hat','MCWars',25);
        draw_head('head','MCWars',25);
    </script>
</div>
EOF;

//--endhtml--//
	    return $registry->output->getTemplate('elib')->helloWorld();
	}
}