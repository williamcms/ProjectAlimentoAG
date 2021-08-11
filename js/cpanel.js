(formOn = (num) => {
	document.getElementById("formulario"+num).style.display = "block";
});
(formOff = (num) => {
	document.getElementById("formulario"+num).style.display = "none";
	window.location.href = window.location.href;
});
(isMobile = () => {
	var isMobile = false; //initiate as false
	// device detection
	if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
	    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
	    isMobile = true;
	}
	return isMobile;
});
$(document).ready(function(){
	(toggleMenu = ($x) =>{
		//Altera valores do menu, mostrando se o menu esta aberto/fechado (sr)
		if(isMobile){
		    var $hm_menu_items = document.getElementById('hm_menu_items');

		    var ariaExpanded = $x.getAttribute('aria-expanded') === 'true';
			
			$x.classList.toggle('change');
			
		    $x.setAttribute('aria-expanded', !ariaExpanded);
		    $hm_menu_items.setAttribute('aria-expanded', !ariaExpanded);
		}
	});

	$('nav a').click(function(){
		$x = document.getElementById('hm_menu');
		toggleMenu($x);
	});
	
	$(window).scroll(function() {
		if ($(window).scrollTop() > 500) {
    		$("#logo").addClass('logo_m');
    		$("#logo").removeClass('logo_a');
    		$("#logo").attr('src','images/logo_s.png');
  		}else{
    		$("#logo").addClass('logo_a');
    		$("#logo").removeClass('logo_m');
    		$("#logo").attr('src','images/logo.png');
  		}

	    if ($(window).scrollTop() > 200) {
	      $('#backTop').addClass('show');
	    } else {
	      $('#backTop').removeClass('show');
	    }
  	});

	$('#backTop').on('click', function(e) {
	    e.preventDefault();
	    $('html, body').animate({scrollTop:0}, '300');
	});
	//Remove caminho de links desativados
	(function(){
	    for(let i = 0; i < $('a[disabled]').length; i++){
	        $('a[disabled]')[i].href = '#';
	    }
	})();
	//Fecha a notificação
	$('#closeEmailNotification').on('click', function(e){
		$('#successEmailNotification').css('opacity', '0');
	});
	//Range
	$('#range_input').on('input', function(e){
		$('#range_input_value').html(this.value);
	});
	$('#range_input2').on('input', function(e){
		$('#range_input_value2').html(this.value);
	});
	$('#password').on('input', function(e){
		if($('#password').val() !== $('#password2').val()){
			$('#password2').css('border', '1px solid var(--warning)');
			$('#password2').css('box-shadow', '0 0 3px 2px var(--warning)');
			$('#confirmPassword').html('(As senhas não coincidem)');
			$('#confirmPassword2').html('(As senhas não coincidem)');
		}else{
			$('#password2').css('border', '1px solid var(--green)');
			$('#password2').css('box-shadow', '0 0 3px 2px var(--green)');
			$('#confirmPassword').html('<span></span>');
			$('#confirmPassword2').html('<span></span>');
		}
	});
	$('#password2').on('input', function(e){
		if($('#password').val() !== $('#password2').val()){
			$('#password2').css('border', '1px solid var(--warning)');
			$('#password2').css('box-shadow', '0 0 3px 2px var(--warning)');
			$('#confirmPassword').html('(As senhas não coincidem)');
			$('#confirmPassword2').html('(As senhas não coincidem)');
		}else{
			$('#password2').css('border', '1px solid var(--green)');
			$('#password2').css('box-shadow', '0 0 3px 2px var(--green)');
			$('#confirmPassword').html('<span></span>');
			$('#confirmPassword2').html('<span></span>');
		}
	});
});