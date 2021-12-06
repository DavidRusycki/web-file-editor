<?php

const ARQUIVO = './arquivo.txt';

/**
 * Função que inia o sistema.
 */
function init() : void {
    validaParametro();
}

/**
 * Valida os parametros da requisição.
 */
function validaParametro() : bool {
    setAcaoCaseNull();

    switch ($_GET['acao']) {
        case 'read':
            montaTelaArquivo();
            break;
        case 'alterar':
            montaTelaAlterar();
            break;
        case 'modifica':
            modificaArquivo();
            break;
        case 'deletar':
            deletaLinha();
            break;
        case 'incluir':
            montaTelaIncluir();
            break;
    }

    return true;
}

/**
 * Caso não tenha ação define uma ação generica.
 */
function setAcaoCaseNull() : void {
    if (!isset($_GET['acao'])) {
        $_GET['acao'] = 'read';
    }
}

/**
 * Abre a baseada no arquivo.
 */
function montaTelaArquivo() : void {
    echo '<table class="table">';

    foreach(getArquivo() as $iIndice => $sLinha) {
        echo '<tr>';
        echo '<td>';
        echo "<p>{$sLinha}</p>";
        echo '</td>';
        echo '<td>';
        echo "<a class=\"btn btn-warning\" href=\"?acao=alterar&linha={$iIndice}\">Alterar</a>";
        echo "<a class=\"btn btn-danger\" href=\"?acao=deletar&linha={$iIndice}\">Deletar</a>";
        echo '</td>';
        echo '</tr>';
    }
    
    echo "<a class=\"btn btn-success\" href=\"?acao=incluir\">Incluir</a>";

    echo '</table>';
}

/**
 * Monta a tela para alterar uma linha do arquivo.
 */
function montaTelaAlterar() : void {

    $sValue = getArquivo()[$_GET['linha']];

    echo "<form action=\"?acao=modifica&linha={$_GET['linha']}\" method=\"POST\">";

        echo "<input type=\"text\" name=\"conteudo\" id=\"conteudo\" value=\"{$sValue}\">";
        echo "<input type=\"submit\" value=\"Alterar\">";

    echo "</form>";
}

/**
 * Modifica o arquivo de acordo com a requisição.
 */
function modificaArquivo() : void {
    $aFile = getArquivo();
    $aFile[intval($_GET['linha'])] = ' '.$_POST['conteudo'];
    file_put_contents(ARQUIVO, $aFile);
    redirectHome();
}

/**
 * Deleta uma linha do arquivo.
 */
function deletaLinha() : void {
    $aFile = getArquivo();
    unset($aFile[$_GET['linha']]);
    file_put_contents(ARQUIVO, $aFile);
    redirectHome();
}


/**
 * Monta a tela para incluir uma linha do arquivo.
 */
function montaTelaIncluir() : void {
    
    $iLinha = 0;

    if (count(getArquivo())) {
        $iLinha = (count(getArquivo()) + 2);
    }

    echo "<form action=\"?acao=modifica&linha={$iLinha}\" method=\"POST\">";

        echo "<input type=\"text\" name=\"conteudo\" id=\"conteudo\">";
        echo "<input type=\"submit\" value=\"Incluir\">";

    echo "</form>";

}

/**
 * Le e retorna o arquivo em um array.
 */
function getArquivo() : array {
    return file(ARQUIVO);
}

/**
 * Redireciona para o inicio do sistema.
 */
function redirectHome() : void {
    header('Location: index.php');
}
