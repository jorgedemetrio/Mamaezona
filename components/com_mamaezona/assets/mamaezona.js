<<<<<<< .mine
var MAMAEZONA = {versao:'2017.20.28',titulo:'Mam�ezona'};
 
=======
var MAMAEZONA = {versao:'2018.24.32',titulo:'Mamãezona'};

document.mamaezona = MAMAEZONA;
>>>>>>> .r29
 


MAMAEZONA.ProcessandoMensagens = false;
MAMAEZONA.ProcessandoMensagensInterval = null;
MAMAEZONA.QuantidadeMensagens = null;
MAMAEZONA.QuantidadeSets =null;


function TestaCPF(strCPF) {
    var Soma;
    var Resto;
    Soma = 0;
    if (strCPF == "00000000000") return false;
    for (i=1; i<=9; i++)
                    Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11))
            Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)) )
            return false;
    Soma = 0;
    for (i = 1; i <= 10; i++)
            Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11))
            Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11) ) )
            return false;
    return true;
}

alert = function(msg){
    jQuery('#modalWindowtitle').html(window.document.title);
    jQuery('#modalWindowbody').html(msg);
    jQuery('#modalWindowok').css('display','none');
    jQuery('#modalWindow').modal('show');
    jQuery('#modalWindowbody').removeClass('alert');
    jQuery('#modalWindowbody').removeClass('alert-warning');
    jQuery('#modalWindowbody').removeClass('alert-danger');
    jQuery('#modalWindowbody').addClass('alert alert-danger');
}

info = function(msg){
    jQuery('#modalWindowtitle').html(window.document.title);
    jQuery('#modalWindowbody').html(msg);
    jQuery('#modalWindowok').css('display','none');
    jQuery('#modalWindow').modal('show');
    jQuery('#modalWindowbody').removeClass('alert');
    jQuery('#modalWindowbody').removeClass('alert-warning');
    jQuery('#modalWindowbody').removeClass('alert-danger');
    jQuery('#modalWindowbody').addClass('alert alert-warning');
}




 /**
  * Message box
  */
 MAMAEZONA.alert = function(msg){
	alert(msg);
 }
 
 MAMAEZONA.alert = function(msg){
	info(msg);
 }
 
 /**
  * Verifica se existe mensagem para o usuaio logado;
  */
 MAMAEZONA.hasMensagens = function(){
	 
 }
 
 MAMAZONA.sugerirAssuntos = function(origem,destino){
	 
 }
 
 MAMAZONA.AbrirModalAlerta = function(titulo, texto, legandaBotaoOk, destino){
     jQuery('#modalWindowtitle').html(titulo);
     jQuery('#modalWindowbody').html(texto);
     jQuery('#modalWindowok').attr('href',destino);
     jQuery('#modalWindowok').html(legandaBotaoOk);
     jQuery('#modalWindowok').css('display','');
     jQuery('#modalWindow').modal('show');
     jQuery('#modalWindowbody').removeClass('alert');
     jQuery('#modalWindowbody').removeClass('alert-warning');
     jQuery('#modalWindowbody').removeClass('alert-danger');
     jQuery('#modalWindowbody').addClass('alert alert-warning');
}
 
 MAMAEZONA.FrameModal = function(titulo, url, legandaBotaoOk, destino, tamanho){
     jQuery('#modalWindowtitle').html(titulo);
     jQuery('#modalWindowbody').html('<iframe src="'+url+'" style="width:100%; max-height:350px;height: '+(tamanho>350?'100%':tamanho+'px')+'" id="iFrameModal"></iframe>');
     jQuery('#modalWindowok').attr('href',destino);
     jQuery('#modalWindowok').html(legandaBotaoOk);
     jQuery('#modalWindowok').css('display','');
     jQuery('#modalWindow').modal('show');
     jQuery('#modalWindowbody').removeClass('alert-warning');
     jQuery('#modalWindowbody').removeClass('alert-danger');
     jQuery('#modalWindowbody').removeClass('alert');
}


 MAMAEZONA.FrameModalHide = function(){
         jQuery('#modalWindow').modal('hide');
 }


 MAMAEZONA.Processando = function () {
     return {
         show: function() {
                 jQuery('#pleaseWaitDialog').modal('show');
                 jQuery('#pleaseWaitDialog').css('display','');
         },
         hide: function () {
                 jQuery('#pleaseWaitDialog').modal('hide');
                 jQuery('#pleaseWaitDialog').css('display','none');
         },
     };
 };
 
 MAMAEZONA.PostItEffects = function(){
     jQuery(".content-post").css('background','#FFFFBC');
     jQuery(".content-post").css('background','#FFFFBC');
     jQuery(".content-post").css('-webkit-box-shadow','0 15px 10px rgba(0, 0, 0, 0.7)');
     jQuery(".content-post").css('-moz-box-shadow','0 15px 10px rgba(0, 0, 0, 0.7)');
     jQuery(".content-post").css('box-shadow','0 15px 10px rgba(0, 0, 0, 0.7)');
     jQuery(".content-post").css('-webkit-box-shadow','0 1px 4px rgba(0, 0, 0, 0.3), 0 0 60px rgba(0, 0, 0, 0.1) inset');
     jQuery(".content-post").css('-moz-box-shadow','0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset');
     jQuery(".content-post").css('box-shadow','0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset');
     setTimeout(function() {
             jQuery(".content-post").each(function(){
                     $this = jQuery(this);
                     if($this.attr('data-ajustado')!='FEITO'){
                             var P =  Math.floor((Math.random() * 7) *(Math.random()*5>2?-1:1) +1);
                             $this.css('transition','2s');
                             $this.css('-webkit-box-shadow','0 1px 4px rgba(0, 0, 0, 0.3), 0 0 60px rgba(0, 0, 0, 0.1) inset');
                             $this.css('-moz-box-shadow','0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset');
                             $this.css('box-shadow','0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset');
                             $this.css('background','#FFFFBC');
                             $this.css('-ms-transform','rotate('+P+'deg)');
                         $this.css('-webkit-transform','rotate('+P+'deg)');
                         $this.css('transform','rotate('+P+'deg)');
                         $this.attr('data-ajustado','FEITO');
                     }
             });
     }, 2000);
}
 
 MAMAEZONA.Notificar = function (titulo, mensagem, url){
     Notification.requestPermission(function(dado){
             if(dado=='granted'){
                     var notification = new Notification("Mamãezona: "+titulo, {
                         icon: 'https://youtuber.mamaezona.com.br/components/com_mamaezona/mamaezona.png',
                         body: mensagem
                     });
             notification.onclick = function() {
                 window.location=url;
                 window.focus();
             }
             }
     });
}


MAMAEZONA.tipoSelecionado = function(tabela,tipo,assunto,addNew){
	if(tipo.attr('alterado')=='S'){
		if(addNew){
			var html="<tr>";
				
			html+="<tr>";
			jQuery('#'+tabela.attr('id')+' > tbody:last-child').append(html);
		}
	}
	tipo.attr('alterado','S');
	MAMAEZONA.carregarAssunto(tipo,assunto);
}




MAMAEZONA.carregarTipo = function(combo,apenasUm){
	combo.find('option')
				.remove()
				.end()
				.append('<option value=""></option>');
	

				
	if(apenasUm){
		for(var i=0; i<MAMAEZONA.tipos.length;i++){
			var tipo = MAMAEZONA.tipos[i];
			var assuntos = tipo.assuntos;
			var optGroup = jQuery("<optgroup label='"+tipo.titulo+"'></optgroup>");
			for(var j=0; i<assuntos.length;j++){
				optGroup.append(jQuery('<option>', {
					value: assuntos[j].token,
					text: assuntos[j].titulo,
					selected : (optGroup.attr('valor')!=null && optGroup.attr('valor')!=''  && optGroup.attr('valor')== assuntos[j].token)
				});
			}
			combo.append(optGroup);
		}
	}
	else{
		for(var i=0; i<MAMAEZONA.tipos.length;i++){
			var tipo = MAMAEZONA.tipos[i];
			combo.append(jQuery('<option>', {
				value: tipo.token,
				text: tipo.titulo,
				selected : (combo.attr('valor')!=null && combo.attr('valor')!=''  && combo.attr('valor')== tipo.token)
			}));
			
			if(combo.attr('valor')!=null && combo.attr('valor')!=''  && combo.attr('valor')== tipo.token){
				MAMAEZONA.tipoSelecionado(jQuery('#'+combo.attr('tabela')), combo, jQuery('#'+combo.attr('assunto')),false);
			}
		}
		combo.change(function(obj){
			var $obj = jQuery(obj);
			MAMAEZONA.tipoSelecionado(jQuery('#'+$obj.attr('tabela')), $obj, jQuery('#'+$obj.attr('assunto')),false);
		});
	}
}

MAMAEZONA.carregarAssunto = function(tipoCmb,assuntoCmb){
	for(var i=0; i<MAMAEZONA.tipos.length;i++){
		var tipo = MAMAEZONA.tipos[i];
		var assuntos;
		

		
		
		if(tipo.token==tipoCmb.val()){
			assuntos = tipo.assuntos;
			assuntoCmb.find('option')
				.remove()
				.end()
				.append('<option value=""></option>');
			for(var j=0; j<assuntos.length;j++){
				//after
				assuntoCmb.append(jQuery('<option>', {
					value: assuntos[j].token,
					text: assuntos[j].titulo,
					selected : (assuntoCmb.attr('valor')!=null && assuntoCmb.attr('valor')!=''  && assuntoCmb.attr('valor')== assuntos[j].token)
				}));
			}
			MAMAEZONA.carregarDetalheAssunto();
		}
	}
} 

MAMAEZONA.carregarDetalheAssunto = function(){
	var $tipos = ('.tipos');
	var arrayCombinacao = new Array();
	
	var contador = 0;
	var $comboAssunto =null;
	for(var i=0; i< $tipos.length){
		$comboAssunto = jQuery('#'+$tipos.attr('assunto'));
		$comboAssunto.prop('disabled',false);
		if(!$comboAssunto.val().empty()){
			arrayCombinacao[contador++] = $tipos.val()+'-'+$comboAssunto.val();
		}
	}
	
	for(var i=0; i< $tipos.length){
		$comboAssunto = jQuery('#'+$tipos.attr('assunto'));
		$comboAssunto.prop('disabled',false);
		for(var j=0; j<arrayCombinacao.length;j++){
			if(arrayCombinacao[contador++] == $tipos.val()+'-'+$comboAssunto.val()){
				$comboAssunto.prop('disabled',true);				
			}
		}
	}
}




MAMAEZONA.URLNoCache= function (url){
    return url+(url.indexOf('?')>0?'&':'?')+'date='+(new Date()).getTime();
}

MAMAEZONA.ResetConfig = function(){
/*    jQuery('.checkbox-iten').each(function(){
            $objetoRef = jQuery(this);
            $hiddenRef = jQuery('#'+$objetoRef.attr('data-hidden-id'))
            if($hiddenRef.val()==$objetoRef.attr('data-hidden-value')){
                    $objetoRef.html($objetoRef.attr('data-hidden-label')+' <span class="glyphicon glyphicon-check"></span>');
            }
            else{
                    $objetoRef.html($objetoRef.attr('data-hidden-label')+' <span class="glyphicon glyphicon-unchecked"></span>');
            }
            $objetoRef.click(function(){
                    $objeto = jQuery(this);
                    $hidden = jQuery('#'+$objeto.attr('data-hidden-id'));
                    if($hidden.val()==$objeto.attr('data-hidden-value')){
                            $hidden.val('');
                            $objeto.html($objeto.attr('data-hidden-label')+' <span class="glyphicon glyphicon-unchecked"></span>');

                    }
                    else{
                            $objeto.html($objeto.attr('data-hidden-label')+' <span class="glyphicon glyphicon-check"></span>');
                            $hidden.val($objeto.attr('data-hidden-value'));
                    }
                    if($objeto.attr('onchange') && $objeto.attr('onchange')!=""){
                            eval($objeto.attr('onchange'));
                    }
            });
    });*/
};


jQuery(document).ready(function(){

	MAMAEZONA.ProcessandoMensagensInterval = setInterval(function(){
		MAMAEZONA.CarregarDadosInformativos();
    }, 30000);


	MAMAEZONA.CarregarDadosInformativos();
	
	
    jQuery(".validate-numeric").mask("#.##0,00", {reverse: true});
    jQuery(".validate-inteiro").mask("9999999999999");
    jQuery(".validate-cep").mask("99999-999");
    jQuery(".validate-cpf").mask("999.999.999-99");

    jQuery(".validate-data").mask("99/99/9999", {placeholder: "__/__/____"});
    jQuery("input[data-validation='date']").mask("99/99/9999", {placeholder: "__/__/____"});
    jQuery(".validate-telefone").mask("(99) 99999-9999");
    jQuery(".validate-telefone-simples").mask("99999-9999");
    
    
    jQuery(".estado").change(function(){
        $objeto = jQuery(this);
        $ObjetoCidade = jQuery("#"+$objeto.attr("data-carregar"));
        $ObjetoCidade.empty();
        $ObjetoCidade.append(new Option("", ""));
        jQuery.post('index.php?option=com_mamaezona&view=perfil&task=cidadeJson',{
                uf: $objeto.val()}, function(dado){
                for(var i=0; i<dado.length;i++){
                        var option = new Option(dado[i].nome, dado[i].id);
                        $ObjetoCidade.append(option);
                        if($ObjetoCidade.attr('data-value')==dado[i].id){
                                option.selected = 'selected';
                        }
                }
        },'json');
    });

    MAMAEZONA.ResetConfig();
	jQuery('.assuntos').change(function(){
		MAMAEZONA.carregarDetalheAssunto();		
	});
}



var ptBRValidation = {
    errorTitle: 'Falha ao enviar formulário!',
    requiredFields: 'Você deve preencher todos os campos obrigatórios.',
    badTime: 'Você deve colocar a hora correta.',
    badEmail: 'Você não forneceu um e-mail válido',
    badTelephone: 'Você não forneceu numero correto de telefone.',
    badSecurityAnswer: 'Você não forneceu uma resposta correta de segurança.',
    badDate: 'Você não forneceu uma data correta.',
    lengthBadStart: 'O dado de entrada deve estar entre ',
    lengthBadEnd: ' caracteres',
    lengthTooLongStart: 'Essa valor é maior que ',
    lengthTooShortStart: 'Essa valor é menor que ',
    notConfirmed: 'A informação não pode ser confirmada.',
    badDomain: 'Valor de dominio incorreto.',
    badUrl: 'O valor não é um URL válida.',
    badCustomVal: 'O valor não está correto.',
    andSpaces: ' e espaços ',
    badInt: 'O valor não é um número válido.',
    badSecurityNumber: 'Your social security number was incorrect',
    badUKVatAnswer: 'Incorrect UK VAT Number',
    badStrength: 'A senha não é fornte o suficiente.',
    badNumberOfSelectedOptionsStart: 'You have to choose at least ',
    badNumberOfSelectedOptionsEnd: ' respostas ',
    badAlphaNumeric: 'O campo só pode ter valores alfanumericos. ',
    badAlphaNumericExtra: ' e ',
    wrongFileSize: 'O arquivo que está tentando enviar é muito grande. (Máximo %s)',
    wrongFileType: 'Somente arquivos do tipo %s são permitidos',
    groupCheckedRangeStart: 'Por favor selecione entre ',
    groupCheckedTooFewStart: 'Please choose at least ',
    groupCheckedTooManyStart: 'Porfavor selecione o máximo de ',
    groupCheckedEnd: ' item(ns)',
    badCreditCard: 'The credit card number is not correct',
    badCVV: 'The CVV number was not correct',
    wrongFileDim : 'tamanho da imagem inválido,',
    imageTooTall : 'a imagem não pode ser maior que',
    imageTooWide : 'a imagem não pode ser menor que',
    imageTooSmall : 'a imagem é muito pequena',
    min : 'minimo',
    max : 'máximo',
    imageRatioNotAccepted : 'Imagem não aceita'
};

