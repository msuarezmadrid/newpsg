<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession();
	$modulo="logbook";
	
	$filtro = $_GET["filtro"] ?? $_POST["filtro"] ?? '';
	
	if( empty($filtro) ){
		$filtro="boolean";
	} ?>
	
	<h1 align="CENTER">Buscar en base</h1>
	<form name='test' action="bcon_query.php" method="post">
		<p>
			The boolean full-text search capability supports the following operators:
		</p>
		<DL COMPACT>
			<DT><CODE>+</CODE>
			<DD>
			A leading plus sign indicates that this word <STRONG>must be</STRONG>
			present in every row returned.
			<DT><CODE>-</CODE>
			<DD>
			A leading minus sign indicates that this word <STRONG>must not be</STRONG>
			present in any row returned.
			<DT><CODE></CODE>
			<DD>
			By default (when neither plus nor minus is specified) the word is optional,
			but the rows that contain it will be rated higher.
			<DD>
			These two operators are used to change a word's contribution to the
			relevance value that is assigned to a row.  The <CODE>&#60;</CODE> operator
			decreases the contribution and the <CODE>&#62;</CODE> operator increases it.
			<DT><CODE>( )</CODE>
			<DD>
			Parentheses are used to group words into subexpressions.
			<DT><CODE>~</CODE>
			<DD>
			A leading tilde acts as a negation operator, causing the word's
			contribution to the row relevance to be negative. It's useful for marking
			noise words. A row that contains such a word will be rated lower than
			others, but will not be excluded altogether, as it would be with the
			<CODE>-</CODE> operator.
			<DT><CODE>*</CODE>
			<DD>
			An asterisk is the truncation operator. Unlike the other operators, it
			should be <STRONG>appended</STRONG> to the word, not prepended.
			<DT><CODE>"</CODE>
			<DD>
			The phrase, that is enclosed in double quotes <CODE>"</CODE>, matches only
			rows that contain this phrase <STRONG>literally, as it was typed</STRONG>.
		</DL>
		
		<label for="descripcion">Buscar:</label>
		<input type="text" id="descripcion" name="descripcion" />
		<input type="radio" name="filtro" value="boolean" <?php if ($filtro == "boolean") echo "checked"; ?> /> Exacto (de acuerdo a instrucciones)
		<input type="radio" name="filtro" value="no" <?php if ($filtro == "no") echo "checked"; ?> /> Alguna de las palabras
		
		<p>
			<input type="submit" value="Buscar" />&nbsp;<input type="reset" value="Borrar" />
		</p>
	</form>