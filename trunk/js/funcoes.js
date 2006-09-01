
/*
parametros 
	form = document.nomeDoForm --> form a ser bubmetido
	strAcao = acaoAExecutar    --> ? a a??o que o form esta executando
	id = identificador         --> referencia para atualiza??o (passar -1 quando n?o houver identificador)
*/
function submeteForm(form, strAcao, id) {
    form.acao.value = strAcao;
    if (id != -1) {
        form.id.value = id;
    }
    form.submit();
}
function dataCompleta(objeto) {
    if (event.keyCode != 8) {
        tamanho = objeto.value.length;
        if (((tamanho == 2) && (objeto.value.substring(1, 2) != "/")) || ((tamanho == 5) && !((objeto.value.substring(3, 4) == "/") || (objeto.value.substring(4, 5) == "/")) && (objeto.value.substring(1, 2) != "/"))) {
            objeto.value = objeto.value + "/";
        }
    }
}
function comparaData(dataMenor, dataMaior) {
    dataMenorAMD = dataMenor.substring(6, 10) + "" + dataMenor.substring(3, 5) + "" + dataMenor.substring(0, 2);
    dataMaiorAMD = dataMaior.substring(6, 10) + "" + dataMaior.substring(3, 5) + "" + dataMaior.substring(0, 2);
    if (dataMaiorAMD < dataMenorAMD) {
        alert("A data final deve ser maior que a data inicial");
        return false;        
    } else {
        return true;
    }
}
function dataVerifica(objeto) {
    problema = false;
    qtBarra = 0;
    tamanho = objeto.value.length;
    if (tamanho > 4) {
        caracteres = "01234567890/";
        for (i = 0; i < tamanho; i++) {
            algarismo = objeto.value.substring(i, i + 1);
            if ((caracteres.search(algarismo) == -1) && !(problema)) {
                msg = "Data inv\xe1lida!\n";
                msg += " - Data informada: " + objeto.value + "\n\n";
                msg += "Verifica\xe7\xe3o: \n";
                msg += " - Caracter informado inv\xe1lido;\n\n";
                msg += "Informa\xe7\xf5es \xfateis: \n";
                msg += " - Caracteres v\xe1lidos para compor a data: \n   -> 1,2,3,4,5,6,7,8,9,0 e '/'(separador);\n";
                alert(msg);
                problema = true;
            }
            if (algarismo == "/") {
                qtBarra++;
            }
        }
        if (!(problema)) {
            if (qtBarra > 2) {
                msg = "Data inv\xe1lida!\n";
                msg += " - Data informada: " + objeto.value + "\n\n";
                msg += "Verifica\xe7\xe3o: \n";
                msg += " - Caracter informado inv\xe1lido;\n\n";
                msg += "Informa\xe7\xf5es \xfateis: \n";
                msg += " - Caracteres v\xe1lidos para compor a data: \n   -> 1,2,3,4,5,6,7,8,9,0\te '/'(separador);\n";
                msg += " - Formato da data: dd/mm/aaaa";
                alert(msg);
                problema = true;
            }
        }
        if (!(problema)) {
            for (i = 0; i < tamanho; i++) {
                algarismo = objeto.value.substring(i, i + 1);
                if (algarismo == "/") {
                    if (i == 1) {
                        objeto.value = "0" + objeto.value;
                    } else {
                        if (i == 4) {
                            objeto.value = objeto.value.substring(0, 3) + "0" + objeto.value.substring(3, 11);
                        }
                    }
                }
            }
            tamanho = objeto.value.length;
            if (tamanho < 10) {
                ano = parseFloat(objeto.value.substring(6, 10));
                if ((ano > 0) && (ano < 30)) {
                    anoi = "20";
                    if (ano < 10) {
                        anoi = "200";
                    }
                    objeto.value = objeto.value.substring(0, 6) + "" + anoi + ano;
                }
                if ((ano > 29) && (ano < 100)) {
                    objeto.value = objeto.value.substring(0, 6) + "19" + ano;
                }
                if ((ano > 99) && (ano < 999)) {
                    objeto.value = objeto.value.substring(0, 6) + "0" + ano;
                }
            }
        }
        tamanho = objeto.value.length;
        dia = objeto.value.substring(0, 2);
        mes = objeto.value.substring(3, 5);
        ano = objeto.value.substring(6, 10);
        barra1 = objeto.value.substring(2, 3);
        barra2 = objeto.value.substring(5, 6);
        if (!(problema)) {
            if ((barra1 != "/") || (barra2 != "/")) {
                alert("Formato da data inv\xe1lido. Exemplo: 01/01/2002");
                problema = true;
            }
        }
        if (!(problema)) {
            if (tamanho != 10) {
                msg = "Ano informado inv\xe1lido!\n";
                msg += " - Ano Informado: " + ano + "\n\n";
                msg += "Verifica\xe7\xe3o: \n";
                msg += " - Ano informado diferente do formato (aaaa);\n\n";
                msg += "Informa\xe7\xf5es \xfateis: \n";
                msg += " - O ano deve conter 4 caracteres;\n";
                alert(msg);
                problema = true;
            }
        }
        if (!(problema)) {
            if ((parseFloat(mes) > 12) || (parseFloat(mes) < 1)) {
                msg = "M\xeas informado inv\xe1lido!\n";
                msg += " - M\xeas Informado: " + mes + "\n\n";
                msg += "Verifica\xe7\xe3o: \n";
                msg += " - M\xeas informado maior que 12;\n";
                msg += " - M\xeas Informado menor que 01;\n\n";
                msg += "Informa\xe7\xf5es \xfateis: \n";
                msg += " - M\xeas informado: " + mes + ";\n";
                alert(msg);
                problema = true;
            }
        }
        if (!(problema)) {
            maiorDia = new Date(ano, mes, 0);
            maiorDia = maiorDia.getDate();
            if ((parseFloat(dia) > maiorDia) || (parseFloat(dia) < 1)) {
                msg = "Dia informado inv\xe1lido!\n";
                msg += " - Dia Informado: " + dia + "\n\n";
                msg += "Verifica\xe7\xe3o: \n";
                msg += " - Dia informado n\xe3o existe no m\xeas informado;\n\n";
                msg += "Informa\xe7\xf5es \xfateis: \n";
                msg += " - M\xeas informado: " + mes + ";\n";
                msg += " - \xdaltimo dia do m\xeas: " + maiorDia + ";";
                alert(msg);
                problema = true;
            }
        }
    } else {
        if (tamanho > 0) {
            msg = "Data inv\xe1lida!\n";
            msg += " - Data informada: " + objeto.value + "\n\n";
            msg += "Verifica\xe7\xe3o: \n";
            msg += " - Data incompleta;\n\n";
            msg += "Informa\xe7\xf5es \xfateis: \n";
            msg += " - Formato da data: dd/mm/aaaa";
            alert(msg);
            problema = true;
        }
    }
    if (problema) {
        objeto.value = "";
        objeto.focus();
        return true;
    }
}
function enterKey(evtKeyPress) {


    var nTecla = 0;
    if (document.all) {
        nTecla = evtKeyPress.keyCode;
    } else {
        nTecla = evtKeyPress.which;
    }
	
    if (nTecla == 13) { // 13 == Enter
       return true;
   } else {
       return false;
   }
}