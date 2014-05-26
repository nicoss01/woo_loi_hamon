<?php
/**
 * Plugin Name: Woocommerce - Loi Hamon
 * Depends: Woocommerce
 * Plugin URI: http://www.erreurs404.net
 * Description: Ce plugin permet de mettre en place facilement certaines obligations de la loi Hamon 2014
 * Version: 1.0.0
 * Author: Nicolas Grillet
 * Author URI: http://www.erreurs404.net
 * License: details de la license
 */
include dirname(__FILE__)."/dependances.php";
include dirname(__FILE__)."/widget.php";
include dirname(__FILE__)."/shortcode.php";

class Woo_Loi_Hamon
{
    // variable d'options
    private $options;
	
	/* Constructeur */
    public function __construct()
    {
		// Initiation
        add_action( 'admin_menu', array( $this, 'ajout_du_lien_admin' ) );
		add_filter( 'woocommerce_available_payment_gateways',array( $this, 'ajout_texte_lien' ) );
		add_filter( 'woocommerce_email_classes', array( $this, 'ajout_email_confirmation_retractation') );
    }
	public function ajout_email_confirmation_retractation( $email_classes ) {
		require( 'class-retractation-email.php' );
		$email_classes['WC_Retractation_Email'] = new WC_Retractation_Email();
		return $email_classes;
	}
    public function ajout_texte_lien($cc){
		$a=array();
		foreach($cc as $c){
			$b=$c;
			$b->title=str_ireplace("paiement par ","",$b->title)." avec obligation de paiement";
			$a[]=$b;
		}
		return $a;
	}
    // Fonction initialisant la création du lien vers la page d'administration
    public function ajout_du_lien_admin()
    {
         add_submenu_page(
			// ID de la page parente
			'woocommerce',
			// Titre de la page
            'Configuration - Loi Hamon', 
            'Loi Hamon', 
			// Capacité nécessaire pour afficher le lien
            'manage_options', 
			// ID de la page d'admin
            'woo-loi-hamon', 
			// Fonction a appeler pour afficher la page d'admin
            array( $this, 'page_administration' )
        );
    }

    public function page_administration()
    {
		if(isset($_POST['submit'])){
			$config=array(
				'tel'=>strip_tags($_POST['tel']),
				'fax'=>strip_tags($_POST['fax']),
				'societe'=>strip_tags($_POST['societe']),
				'adresse'=>strip_tags($_POST['adresse']),
				'ville'=>strip_tags($_POST['ville']),
				'cp'=>strip_tags($_POST['cp']),
				'email'=>strip_tags($_POST['email'])
			);
			update_option('config',$config);
			echo "<div class='updated'>Paramètres enregistrés !</div>";
		}
        $this->options = get_option( 'config' );
        ?>
        <div class="wrap">
            <h2>Paramètres - Loi Hamon</h2>           
            <form method="post" action="admin.php?page=woo-loi-hamon">
            <h3 class="title">Gestion du droit de rétractation</h3>
            <p>Cette extension contient : <ul>
            <li> - Un Widget permettant d'afficher dans une sidebar les informations obligatoires à propos du droit de rétractation</li>
            <li> - Un shortcode <code>[woo_loi_hamon_formulaire]</code>, permettant d'afficher le formulaire de demande de rétractation</li>
            <li> - Un shortcode <code>[woo_loi_hamon_adresse]</code>, permettant d'afficher l'adresse postale où envoyer la demande de rétractation</li>
            <li> - Un système automatique qui ajoute les mentions obligatoires sur le choix des paiements</li>
            </ul></p>
            <p>Veuillez indiquez ici les coordonnées de la personne en charge de la gestion du droit de rétractation.</p>
            <p>Pour consulter le texte complet de la LOI n° 2014-344 du 17 mars 2014 relative à la consommation, <a href="http://www.legifrance.gouv.fr/affichTexte.do;jsessionid=?cidTexte=JORFTEXT000028738036" target="_blank">cliquez ici</a></p>
            <table class="form-table">
            <tr>
				<th scope="row"><label for="tel">Numéro de téléphone</label></th>
                <td><input name="tel" type="text" id="tel" placeholder="00 00 00 00 00" value="<?php echo isset($this->options['tel'])?$this->options['tel']:""; ?>" class="regular-text"></td>
</tr>
            <tr>
				<th scope="row"><label for="fax">Numéro de fax</label></th>
                <td><input name="fax" type="text" id="fax" value="<?php echo isset($this->options['fax'])?$this->options['fax']:""; ?>" placeholder="00 00 00 00 00" class="regular-text"></td>
</tr>
            <tr>
				<th scope="row"><label for="email">Adresse Email</label></th>
                <td><input name="email" type="text" id="email" value="<?php echo isset($this->options['email'])?$this->options['email']:""; ?>" placeholder="retractaction@exemple.fr" class="regular-text"></td>
</tr>
            <tr>
				<th scope="row"><label for="societe">Société</label></th>
                <td><input name="societe" type="text" id="societe" value="<?php echo isset($this->options['societe'])?$this->options['societe']:""; ?>" placeholder="" class="regular-text"></td>
</tr>
            <tr>
				<th scope="row"><label for="adresse">Adresse Postale</label></th>
                <td><input name="adresse" type="text" id="adresse" value="<?php echo isset($this->options['adresse'])?$this->options['adresse']:""; ?>" placeholder="" class="regular-text"></td>
</tr>
            <tr>
				<th scope="row"><label for="ville">Ville</label></th>
                <td><input name="ville" type="text" id="ville" value="<?php echo isset($this->options['ville'])?$this->options['ville']:""; ?>" placeholder="" class="regular-text"></td>
</tr>
            <tr>
				<th scope="row"><label for="cp">Code Postale</label></th>
                <td><input name="cp" type="text" id="cp" value="<?php echo isset($this->options['cp'])?$this->options['cp']:""; ?>" placeholder="00000" class="regular-text"></td>
</tr>
            </table>
            <?php
				// Affichage du bouton "envoyer"
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }
}

$page_administration = new Woo_Loi_Hamon();
if (!function_exists('ajout_page_retractation')) {
	function ajout_page_retractation(){
		if(!is_page("retractation_2014")){
			$my_post = array(
			  'post_name'	  	=> 'retractation_2014',
			  'post_title'   	=> 'Votre droit de rétractation',
			  'post_content'  	=> "
			  <h2>Délai de rétractation</h2>
			  <p>« Art. L. 121-21. – Le consommateur dispose d'un délai de quatorze jours pour exercer son droit de rétractation d'un contrat conclu à distance, à la suite d'un démarchage téléphonique ou hors établissement, sans avoir à motiver sa décision, ni à supporter d'autres coûts que ceux prévus aux articles L. 121-21-3 à L. 121-21-5. Toute clause par laquelle le consommateur abandonne son droit de rétractation est nulle. »</p>
			  <h2>Utilisation de son droit de rétractation</h2>
			  <p>Vous pouvez exercer votre droit de rétractation en envoyant votre demande par courrier libre à l'adresse postal [woo_loi_hamon_adresse], par fax au numéro [woo_loi_hamon_fax], ou par email à [woo_loi_hamon_email].</p>
			  <p>Votre demande doit être faite dans les 14 jours qui suivent votre commande, et doit contenir les informations suivantes : <ul>
			  <li>Nom/Prénom ou Raison Sociale du client</li>
			  <li>Coordonnées</li>
			  <li>Numéro de commande</li>
			  <li>Date d'envoi du courrier</li>
			  </ul></p>
			  <h2>Frais de renvoi des produits</h2>
			  <p>Tous les produits faisant l'objet d'une demande de rétractation, doivent être renvoyés aux frais du client à l'adresse [woo_loi_hamon_adresse] dans un délai maximum de quatorze jours après la date de demande de rétractation.</p>
<p>Ce délai est réputé respecté si vous renvoyez le bien avant l’expiration du délai de quatorze jours.</p>
<p>Votre responsabilité n’est engagée qu’à l’égard de la dépréciation du bien résultant de manipulations autres que celles nécessaires pour établir la nature, les caractéristiques et le bon fonctionnement de ce bien.</p>
<h2>Effets de la rétractation</h2>
<p>En cas de rétractation de votre part du présent contrat, nous vous rembourserons tous les paiements reçus de vous, y compris les frais de livraison (à l’exception des frais supplémentaires découlant du fait que vous avez choisi, le cas échéant, un mode de livraison autre que le mode moins coûteux de livraison standard proposé par nous) sans retard excessif et, en tout état de cause, au plus tard quatorze jours à compter du jour où nous sommes informés de votre décision de rétractation du présent contrat.</p>
<p>Nous procéderons au remboursement en utilisant le même moyen de paiement que celui que vous aurez utilisé pour la transaction initiale, sauf si vous convenez expressément d’un moyen différent; en tout état de cause, ce remboursement n’occasionnera pas de frais pour vous.</p>
<h2>Formulaire de rétractation</h2>
[woo_loi_hamon_formulaire]",
			  'post_status'   	=> 'publish',
			  'post_type'		=> 'page',
			  'post_author'   	=> 1,
			  'comment_status'	=> 'closed'
			);
			wp_insert_post( $my_post,true );
		}
	}
}
add_action( 'activated_plugin', 'ajout_page_retractation', 10, 2 );

// Ajouter des liens rapides dans la page de gestion des plugins
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'liens_pages_extensions' );
function liens_pages_extensions( $links ) {
   $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=woo-loi-hamon') .'">Réglages</a>';
   $links[] = '<a href="http://www.legifrance.gouv.fr/affichTexte.do;jsessionid=?cidTexte=JORFTEXT000028738036" target="_blank">Loi Hamon 2014-344 du 17 mars 2014</a>';
   $links[] = '<a href="http://www.erreurs404.net" target="_blank">www.erreurs404.net</a>';
   return $links;
}
?>