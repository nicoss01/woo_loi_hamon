<?php
 
if ( ! defined( 'ABSPATH' ) ) exit;
 
/**
 * Ajout de l'email de retractation
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WC_Retractation_Email extends WC_Email {
 
	public function __construct(){
	
		$this->id = 'wc_retractation';
		
		$this->title = 'Demande de rétractation';
		
		$this->description = 'Email de confirmation de l\'envoi de rétractation';
		
		$this->subject = 'Demande de rétractation de la boutique {site_title}';
		$this->heading = 'Votre demande a été envoyée ce jour au service gérant les rétractations';
		
		/*$this->template_html  = 'emails/modele_email.php';
		$this->template_plain = 'emails/modele_email.php';*/
		
		add_action( 'woocommerce_envoi_demande_retractation', array( $this, 'trigger' ) );
		parent::__construct();
		
		$this->recipient = $this->get_option( 'email' );
		
		if ( ! $this->recipient )
			$this->recipient = get_option( 'admin_email' );
	}
	public function trigger( $order_id ) {
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}
} 