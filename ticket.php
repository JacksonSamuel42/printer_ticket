<?php


require __DIR__ . '/ticket/autoload.php'; //Nota: se renomeou a pasta para algo que não seja "bilhete" altere o nome nesta linha
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/*
	Este exemplo imprime um
	bilhete de venda de uma impressora térmica
*/


/*
    Aqui, em vez de "POS" (que é o nome da minha impressora)
	escrever o seu nome. Lembre-se de partilhá-la.
	do painel de controlo
*/

$name_printer = "imp1"; 


$connector = new WindowsPrintConnector($name_printer);
$printer = new Printer($connector);
#Mando um número de resposta para saber que está corretamente ligado.
echo 1;
/*
	Vamos imprimir um logotipo
	É opcional. Lembre-se que este
	não vai funcionar em todos os
	Impressoras

	Nota pequena: Recomenda-se que a imagem não seja
	transparente (mesmo que seja png tem que remover o canal alfa)
	e ter uma resolução baixa. No meu caso
	a imagem que uso é de 250 x 250
*/

#Vamos alinhar-nos com o centro a próxima coisa que imprimimos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Vamos tentar carregar e imprimir
	o logotipo
*/
try{
	$logo = EscposImage::load("geek.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){/*Não fazemos nada se houver erro.r*/}

/*
	Agora vamos imprimir um cabeçalho
*/

$printer->text("\n"."Nome da empresa" . "\n");
$printer->text("Endereço: Orquídeas #151" . "\n");
$printer->text("Tel: 454664544" . "\n");
#A data também
date_default_timezone_set("Africa/Luanda");
$printer->text(date("Y-m-d H:i:s") . "\n");
$printer->text("-----------------------------" . "\n");
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("CANT  DESCRIPTION    P.U   IMP.\n");
$printer->text("-----------------------------"."\n");
/*
	Agora vamos imprimir o
	Produtos
*/
	/*Alinhar à esquerda para quantidade e nome*/
	$printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Produtos Galletas\n");
    $printer->text( "2  piza    10.00 20.00   \n");
    $printer->text("Batatas \n");
    $printer->text( "3  piza    10.00 30.00   \n");
    $printer->text("Doritos \n");
    $printer->text( "5  piza    10.00 50.00   \n");
/*
	Terminamos a impressão.
	produtos, agora vai o total
*/
$printer->text("-----------------------------"."\n");
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text("SUBTOTAL: $100.00\n");
$printer->text("IVA: $16.00\n");
$printer->text("TOTAL: $116.00\n");


/*
	Também podemos colocar um rodapé
*/
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("Muito obrigado pela sua compra.\n");



/*Alimentamos o papel 3 vezes*/
$printer->feed(3);

/*
	Cortamos o papel. Se a nossa impressora
	não tem apoio para isso, não vai gerar
	sem engano
*/
$printer->cut();

/*
	Através da impressora enviamos um pulso.
	Isto é útil quando o temos ligado
	por exemplo, para uma gaveta
*/
$printer->pulse();

/*
	Para realmente imprimir, temos que "fechar"
	ligação à impressora. Lembre-se de incluir isto no final de todos os ficheiros
*/
$printer->close();

?>