<?php



class public_elib_minecraft_cabinet extends ipsCommand
{
    public function doExecute( ipsRegistry $registry )
    {	
        $this->lang->loadLanguageFile( array( 'public_lang' ), 'elib' );
        $this->registry->output->setTitle( $this->lang->words['elib_text_hw'] );
        $this->registry->output->addNavigation( $this->lang->words['elib_text_hw'], NULL );
		if($this->settings['elib_settings_enabled'])
		{
        $this->registry->output->addContent( $this->registry->output->getTemplate('elib')->helloWorld('Aysmanlich') );
		}
		else{
		$this->registry->output->addContent( $this->registry->output->getTemplate('elib')->disabled() );		
		}
		
        $this->registry->output->sendOutput();
		print $text;
    }
}