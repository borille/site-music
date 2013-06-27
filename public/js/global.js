// Posiciona o foco no campo desejado
// retorno: nenhum
// parâmetros: formInst: índice da instância do formulário ; elementInst: índice da instância do elemento
// instruções: na tag body inserir: onLoad="putFocus(0,0);" -> para o primeiro campo do formulário
function putFocus(formInst, elementInst)
{
    if (document.forms.length > 0)
    {
        document.forms[formInst].elements[elementInst].focus();
    }
}
//------------------------------------------------------------------------------
//valida o CPF digitado
/* Esta função esta com problemas
function ValidarCPF(Objcpf){
    var cpf = Objcpf.value;
    exp = /\.|\-/g
    cpf = cpf.toString().replace( exp, "" ); 
    var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
    var soma1=0, soma2=0;
    var vlr =11;
        
    for(i=0;i<9;i++){
        soma1+=eval(cpf.charAt(i)*(vlr-1));
        soma2+=eval(cpf.charAt(i)*vlr);
        vlr--;
    }       
    soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
    soma2=(((soma2+(2*soma1))*10)%11);
        
    var digitoGerado=(soma1*10)+soma2;
    
    if(digitoGerado!=digitoDigitado){     
        
        alert( digitoGerado );
        alert('CPF Invalido!');      
        return false;
    }
	
    return true;

*/
function ValidarCPF(Objcpf){
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    var cpf = Objcpf.value;
      
    exp = /\.|\-/g
    cpf = cpf.toString().replace( exp, "" ); 
      
    digitos_iguais = 1;
    if (cpf.length < 11)
        return false;
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1))
        {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais)
    {
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
            soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--)
            soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}

//------------------------------------------------------------------------------
//valida numero inteiro com mascara
function mascaraInteiro(evento){
    var tecla;
    if (window.event) { // Internet Explorer
        tecla = event.keyCode;
    }
    else { // Firefox
        tecla = evento.which;
        if (tecla == 8) { // corrige o erro no mozila que não deixa usar backspace
            return true;
        }
    }
    // tecla = 0 corrige o erro das setas
    if (tecla == 0 || tecla >= 48 && tecla <= 57) return true;
    return false
}
//------------------------------------------------------------------------------
//valida o CNPJ digitado
function ValidarCNPJ(ObjCnpj){
    var cnpj = ObjCnpj.value;
    var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
    var dig1= new Number;
    var dig2= new Number;
        
    exp = /\.|\-|\//g
    cnpj = cnpj.toString().replace( exp, "" ); 
    var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));
                
    for(i = 0; i<valida.length; i++){
        dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);  
        dig2 += cnpj.charAt(i)*valida[i];       
    }
    dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
    dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));
        
    if(((dig1*10)+dig2) != digito) {
        alert('CNPJ Invalido!');
        return false;
    }
	
    return true;		
                
}
//------------------------------------------------------------------------------
function ValidaData(DATA) {   
    
    if( DATA.value == "")
    {
        return true;
    }        
    
    var expReg = /^(([0-2]\d|[3][0-1])\/([0]\d|[1][0-2])\/[1-2][0-9]\d{2})$/;
    var msgErro = 'Formato inválido de data.';
    var vdt = new Date();
    var vdia = vdt.getDay();
    var vmes = vdt.getMonth();
    var vano = vdt.getYear();
    if ((DATA.value.match(expReg)) && (DATA.value!='')){
        var dia = DATA.value.substring(0,2);
        var mes = DATA.value.substring(3,5);
        var ano = DATA.value.substring(6,10);
        if((mes==04 && dia > 30) || (mes==06 && dia > 30) || (mes==09 && dia > 30) || (mes==11 && dia > 30)){
            alert("Dia incorreto !!! O mês especificado contém no máximo 30 dias.");
            DATA.focus();
            return false;
        } else{ //1
            if(ano%4!=0 && mes==2 && dia>28){
                alert("Data incorreta!! O mês especificado contém no máximo 28 dias.");
                DATA.focus();
                return false;
            } else{ //2
                if(ano%4==0 && mes==2 && dia>29){
                    alert("Data incorreta!! O mês especificado contém no máximo 29 dias.");
                    DATA.focus();
                    return false;
                } else{ //3
                    //alert ("Data correta!");
                    return true;
                } //3-else
            }//2-else
        }//1-else                       
    } else { //5
        alert(msgErro);
        DATA.focus();
        return false;
    } //5-else
}
//------------------------------------------------------------------------------
/*
função converte o que é tudo que é digitado no campo para maiusculo
exemplo: onKeypress="uppercase();"
 */
function uppercase()
{       
    key = window.event.keyCode;
    
    if ((key > 0x60) && (key < 0x7B)) {
        window.event.keyCode = key-0x20;
    }
}

//-----------------------------------------------------
//Funcao: MascaraMoeda
//Sinopse: Mascara de preenchimento de moeda
//Parametro:
//   objTextBox : Objeto (TextBox)
//   SeparadorMilesimo : Caracter separador de milésimos
//   SeparadorDecimal : Caracter separador de decimais
//   e : Evento
//Retorno: Booleano
//Autor: Gabriel Fróes - www.codigofonte.com.br
//-----------------------------------------------------
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;    
    if(whichCode == 13 || whichCode == 8 || whichCode == 0) return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
            objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}
//--------------------------------------------------------------
//Formata número tipo moeda usando o evento onKeyDown
function formataMoeda(campo,tammax,teclapres,decimal) {    
    
    var tecla = teclapres.keyCode;
    
    vr = Limpar(campo.value,"0123456789");
    tam = vr.length;
    dec=decimal
    
    if (tam < tammax && tecla != 8){
        tam = vr.length + 1 ;
    }

    if (tecla == 8 ){
        tam = tam - 1 ;
    }

    if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 )
    {

        if ( tam <= dec )
        {
            campo.value = vr ;
        }

        if ( (tam > dec) && (tam <= 5) ){
            campo.value = vr.substr( 0, tam - 2 ) + "," + vr.substr( tam - dec, tam ) ;
        }
        if ( (tam >= 6) && (tam <= 8) ){
            campo.value = vr.substr( 0, tam - 5 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ; 
        }
        if ( (tam >= 9) && (tam <= 11) ){
            campo.value = vr.substr( 0, tam - 8 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ;
        }
        if ( (tam >= 12) && (tam <= 14) ){
            campo.value = vr.substr( 0, tam - 11 ) + "." + vr.substr( tam - 11, 3 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ;
        }
        if ( (tam >= 15) && (tam <= 17) ){
            campo.value = vr.substr( 0, tam - 14 ) + "." + vr.substr( tam - 14, 3 ) + "." + vr.substr( tam - 11, 3 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - 2, tam ) ;
        }
    } 

}
//------------------------------------------------------------------------------
// retira caracteres invalidos da string
function Limpar(valor, validos) {    
    var result = "";
    var aux;
    for (var i=0; i < valor.length; i++) {
        aux = validos.indexOf(valor.substring(i, i+1));
        if (aux>=0) {
            result += aux;
        }
    }
    return result;
}
//------------------------------------------------------------------------------            
function isNumber(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
//--------------------------------------------------------------
function textCounter( field, maxlimit ) {
                
    if ( field.value.length > maxlimit )
    {        
        field.value = field.value.substring( 0, maxlimit );
        return false;
    }
}  
//--------------------------------------------------------------
function caminho( controller, action )
{
    var url = self.location.pathname.split( '/' );
	
    if( url[3] == 'index.php' )
    {	
        return ( '/' + url[1] + '/' + url[2] + '/' + url[3] + '/' + controller + '/' + action );
    }
    else
    {
        return ( '/' + url[1] + '/' + url[2] + '/' + url[3] + '/index.php/' + controller + '/' + action );
    }
}
//--------------------------------------------------------------
//funções para o menu
/*
navHover = function() {
    var lis = document.getElementById("navmenu").getElementsByTagName("LI");
    for (var i=0; i<lis.length; i++) {
        lis[i].onmouseover=function() {
            this.className+=" iehover";
        }
        lis[i].onmouseout=function() {
            this.className=this.className.replace(new RegExp(" iehover\\b"), "");
        }
    }
}
if (window.attachEvent) window.attachEvent("onload", navHover);
*/
//--------------------------------------------------------------

function ValidaHora(campo){  
    hrs = (campo.value.substring(0,2));  
    min = (campo.value.substring(3,5));  
    estado = "";  
    if ((hrs < 00 ) || (hrs > 23) || ( min < 00) ||( min > 59)){  
        estado = "errada";  
    }  
  
    if (campo.value == "") {  
        estado = "errada";  
    }  
    if (estado == "errada") {  
        alert("Hora invalida!");  
        campo.focus();  
    }  
}
//------------------------------------------------------------------------------
function confirmDelete( redirectUrl ){
    var btnConfirma ='<a href="' + redirectUrl + '" style="color: green">Confirmar</a>';
    var btnCancela = "<a href=\"#\" onClick=\"$('#confirm-delete').dialog('close')\" style=\"color: red\">Cancelar</a>";

    $('#confirm-delete').html( '<p style="font-size:14px">Essa operação não poderá ser desfeita!</p>' + btnConfirma + btnCancela);
    $('#confirm-delete').dialog('open');
}

function moeda(valor, casas, separador_decimal, separador_milhar){ 
 
    var valor_total = parseInt(valor * (Math.pow(10,casas)));
    var inteiros =  parseInt(parseInt(valor * (Math.pow(10,casas))) / parseFloat(Math.pow(10,casas)));
    var centavos = parseInt(parseInt(valor * (Math.pow(10,casas))) % parseFloat(Math.pow(10,casas)));
 
  
    if(centavos%10 == 0 && centavos+"".length<2 ){
        centavos = centavos+"0";
    }else if(centavos<10){
        centavos = "0"+centavos;
    }
  
    var milhares = parseInt(inteiros/1000);
    inteiros = inteiros % 1000; 
 
    var retorno = "";
 
    if(milhares>0){
        retorno = milhares+""+separador_milhar+""+retorno
        if(inteiros == 0){
            inteiros = "000";
        } else if(inteiros < 10){
            inteiros = "00"+inteiros; 
        } else if(inteiros < 100){
            inteiros = "0"+inteiros; 
        }
    }
    retorno += inteiros+""+separador_decimal+""+centavos;
 
 
    return retorno;
 
}