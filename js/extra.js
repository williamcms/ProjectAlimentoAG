$(document).ready(function(){
	//Efeito da Borda (precisa do plugin/arquivo hoverIntent)
	$('section#clientes .col').hoverIntent(function() {
		$(this).removeClass('noFocus');
	},
	function() {
		$(this).addClass('noFocus');
	});
	//Função Semelhante a um Carrousel
	(exchangeDestaque = (newElement) =>{

		var $original = document.querySelector("#clientes .destaque");

		//Getters
		var title_o =  $original.querySelector(".col").title;
		var image_o = $original.querySelector(".content div").style.backgroundImage;
		var headerText_o = $original.querySelector(".content .destaqueText .headerText").innerText;
		var subText_o = $original.querySelector(".content .destaqueText .subText").innerHTML;
		var subLink_o = $original.querySelector(".content .destaqueText .subLink").innerText;

		var image_n = newElement.querySelector(".col div").style.backgroundImage;
		var title_n = newElement.dataset.title;
		var presentation_n = newElement.dataset.subtext;
		var link_n = newElement.dataset.link;

		//Setters
		newElement.querySelector(".col div").style.backgroundImage = image_o;
		newElement.title = title_o;
		newElement.dataset.title = headerText_o;
		newElement.dataset.subtext = subText_o;
		newElement.dataset.link = subLink_o;

		$original.querySelector(".col").title = title_n;
		$original.querySelector(".content div").style.backgroundImage = image_n;
		$original.querySelector(".content .destaqueText .headerText").innerText = title_n;
		$original.querySelector(".content .destaqueText .subText").innerHTML = presentation_n;
		$original.querySelector(".content .destaqueText .subLink").innerText = link_n;

		if(link_n){
			$original.querySelector("a").href = link_n;
			$original.querySelector("a").setAttribute('onclick','');
			$original.querySelector("a").setAttribute('disabled','false');
		}else{
			$original.querySelector("a").href = '#';
			$original.querySelector("a").setAttribute('onclick','return false;');
			$original.querySelector("a").setAttribute('disabled','true');
		}


	})
});