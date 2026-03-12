<?php
	session_start();
	require "../../autoloader.php";
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfn->checkSession(); 
	$bodyCss=$cCfn->bodyCss();
	$boostrapCss=$cCfn->boostrapCss();
	$functions=$cCfn->functions();
	$jqueryMin=$cCfn->jqueryMin();
	$boostrapJs=$cCfn->boostrapJs();
	$favicon=$cCfn->favicon();
	include($cCfn->siteHeader());
	?>
	
	<h1 align="CENTER">Instrucciones</h1>
	<h3>Bit&aacute;cora</h3>
	<p>
		La idea es llevar un registro de todos los eventos de la red, para as&iacute; poder (1) mantener informado a todo el personal
		de operaciones, (2) tener un historico de eventos donde poder buscar posibles causas/efectos de problemas, (3) evitar tener
		que escribir el mismo texto en varios lugares, y (4) coordinar actividades que estan ocurriendo en varios lugares simultaneamente.</p>
	<p>
		Si se abre una entrada en la bitacora, mientras esta este abierta, agrupara todos los comentarios asociados, independiente de donde o quienes los
		generan. Si la bitacora se asocia, por ejemplo, a un trabajo programado, todo lo que se escriba en la bitacora aparecera en las acciones del trabajo
		programado, y al reves, todo lo que se escriba en el trabajo programado aparecera en la bitacora, mientras esta permanezca abierta.</p>
	<p>
		Otro ejemplo es una falla entre dos nodos. Si se abre una entrada en la bitacora, el personal de ambos nodos se dara cuenta de lo que esta haciendo
		el otro extremo en todo momento, evitando que se produzcan malentendidos.</p>
	<p>
		Otro ejemplo es el traspaso de turnos. Los eventos que no se han solucionado permaneceran disponibles con toda la informacion asociada.</p>
	<p>
		Para apreciar el valor de la informaci&oacute;n que se puede obtener, se recomienda navegar en el log, donde rapidamente uno se puede dar cuenta
		de todo lo que se esta llevando a cabo en la red, en tiempo real.</p>
	<p>
		Una entrada en la bitacora se puede cerrar, y reabrir cuentas veces se desee (seleccionar Fin).</p>
	<p>
		Nuevos comentarios se agregan seleccionando el ID.</p>
	<p>
		Para agregar/editar el campo de Nodo/Servicio, seleccionar este.</p>
	<p>
		No olvidarse de cerrar la entrada en la bitacora, cuando el evento cese.</p>
	<p>
		Los campos de Inicio y Fin de evento se usan principalmente para indicar tiempos de falla. En el caso de, por ejemplo,
		cortes de tramas, es el tiempo desde que la trama fallo, hasta que fue repuesta. En caso de intermitencias se da el tiempo
		total en que la trama estuvo intermitente. Los tiempos son independientes de los tiempos de la bitacora misma, ya que existen
		casos en que la falla dura minutos, y los trabajos asociados al evento pueden durar horas y d&iacute;as.</p>
	<p>
		Una nueva entrada se crea seleccionando Crear Nueva. Si se desea documentar un evento, sin necesidad
		de seguimiento, se puede seleccionar Crear y Cerrar, con lo cual la entrada se creara ya cerrada, con el
		comentario adjunto.</p>
	<p>
		En el caso de eventos que se extiendan en el tiempo, y se decida crear un trabajo programado, tarea, o problema, es
		posible traspasar todo lo ya ingresado usando la (T) que aparece cuando se crea la asociacion. Una vez efectuado,
		se puede cerrar la entrada en la bitacora, ya que el seguimiento se hara a trav&eacute;s del trabajo programado, tarea, o
		problema.</p>
	<p>
		Las entradas en bitacora pueden ser "privadas", en el sentido en que no aparecen en la pantalla principal. Estas entradas
		se ven en los logs, y se usa para administrar eventos de menor importancia asociados a cada usuario, de manera que no
		llenen la pantalla principal. En cualquier momento, se pueda cambiar entre Global y Privada seleccionando Creador.</p>
	