<?php
add_shortcode("woo_loi_hamon_fax","woo_loi_hamon_fax");
add_shortcode("woo_loi_hamon_adresse","woo_loi_hamon_adresse");
add_shortcode("woo_loi_hamon_email","woo_loi_hamon_email");
function woo_loi_hamon_email($atts){
	$c=get_option("config");
	return str_replace("@","(a)",$c['email']);	
}
function woo_loi_hamon_fax($atts){
	$c=get_option("config");
	return $c["fax"];	
}
function woo_loi_hamon_adresse($atts){
	$c=get_option("config");
	return "&laquo; ".$c['societe'].", ".$c['adresse']." - ".$c['cp']." ".$c['ville']." &raquo;";	
}
add_shortcode("woo_loi_hamon_formulaire","woo_loi_hamon_formulaire_retraction_html");
function woo_loi_hamon_formulaire_retraction_html($atts){
	if(isset($_POST['valid'])){
		$message= '<p>'.__('Nom','contact').' : '.strip_tags($_POST['nom']).'</p>';
		$message.= '<p>'.__('Téléphone','contact').' : '.strip_tags($_POST['tel']).'</p>';
		$message.= '<p>'.__('Email','contact').' : '.strip_tags($_POST['email']).'</p>';
		$message.= '<p>'.__('Message','contact').' :<br />'.strip_tags($_POST['message']).'</p>';
		$formulaire='<div class="succes">Votre demande de rétractation a bien été envoyé, vous allez recevoir un email de confirmation d\'envoi dans les plus brefs délais.</div>';
	}else{
		$formulaire = '<form action="" method="post" class="retractation">';
		$formulaire.= '<p><label><span class="label">*Nom</span> : <input type="text" name="nom" required="required" /></label></p>';
		$formulaire.= '<p><label><span class="label">*Prénom</span> : <input type="text" name="prenom" required="required" /></label></p>';
		$formulaire.= '<p><label><span class="label">*Email</span> : <input type="text" name="email" required="required" /></label></p>';
		$formulaire.= '<p><label><span class="label">*Téléphone</span> : <input type="text" name="telephone" required="required" /></label></p>';
		$formulaire.= '<p><label><span class="label">*Numéro de commande</span> : <input type="text" name="commande" required="required" /></label></p>';
		$formulaire.= '<p><label><span class="label">*Date commande</span> : <input type="date" name="date_commande" required="required" /></label></p>';
		$formulaire.= '<p><label><span class="label">*Date de la demande</span> :
		<input type="text" name="date_demande" value="'.date('d/m/Y H:i').'" readonly="readonly" /></label></p>';
		$formulaire.= '<p><input type="submit" name="valid" value="Valider et envoyer ma demande de rétractation immédiatement" /><div class="alert">En cliquant sur le bouton ci-dessus, je confirme envoyer une demande de rétractation dans la pleine possession de mes moyens.</div></p>';
		$formulaire.="</form>";
		$formulaire.= '<style type="text/css">.retractation .label{ display:inline-block;width:180px;} </style>';
	}
	return $formulaire;	
}
?>