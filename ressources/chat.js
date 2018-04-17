window.onload = function() {
	$("#chat").load("./ressources/chat/chat_recevoir.php");
}

setInterval(function() {
	$("#chat").load("./ressources/chat/chat_recevoir.php");
}, 5000);

function envoyerMessage() {
	$('#envoyer').text('En cours');
	
	var message = $('#contenu').val();
	
	$.ajax({
		url: './ressources/chat/chat_envoyer.php',
		type: 'POST',
		data: 'message='+ message,
		dataType: 'json',
		success: function(donnee) {
			$('#envoyer').text('Envoyer');
			
			switch(donnee.retour) {
				case "1":
					$('#erreur_chat').text("Erreur : Le message ne peut être vide").slideDown(500).delay(1000).slideUp(500);
				break;
				
				case "2":
					$('#erreur_chat').text("Erreur : Tu ne peux pas poster un message aussi rapidement").slideDown(500).delay(1000).slideUp(500);
				break;
				
				case "3":
					$("#chat").load("./ressources/chat/chat_recevoir.php");
					$('input, textarea').val('');
				break;
				
				default:
					$('#erreur_chat').text("Erreur : Inconnue").slideDown(500).delay(1000).slideUp(500);
			}
		}
	});
}