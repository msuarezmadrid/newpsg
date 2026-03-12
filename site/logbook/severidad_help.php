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
	<h3>Severidad</h3>
	<p>
		Se ha agregado el campo Severidad en la Bitacora. Esto con la finalidad de agrupar los eventos de acuerdo al Impacto que tienen en el Servicio
		para el cliente final. Este campo es obligatorio.</p>
	<p>
		La asignaci&oacute;n del nivel de severidad al evento permite discriminarlo con respecto a los m&uacute;ltiples 
		incidentes que se producen cotidianamente en el sistema integrado de red. En este contexto el 
		nivel de severidad permite asignarle una prioridad de atenci&oacute;n diferenciada en funci&oacute;n del 
		impacto real o potencial del evento en el servicio.</p>
	<p>
		El nivel de severidad se establece en una primera instancia en base a las pol&iacute;ticas establecidas 
		por la Subgerencia de Operaciones y Mantenimiento y el criterio profesional del personal de 
		Operaciones.</p>
	<p>
		Este texto tiene como finalidad dar las pautas para la clasificaci&oacute;n de eventos</p>
	<h3>NINGUNA</h3>
	<p>
		No existe impacto actual o potencial en el servicio. Se usa para clasificar eventos de rutina que no son alarmas. Ejemplos: Salidas/Entradas a centros de
		conmutaci&oacute;n, backup rutinarios, visitas de mantenci&oacute;n por parte de personal PCS, chequeos de rutina, ejecuci&oacute;n de TP evaluados que no tienen impactos en el servicio.</p>
	<h3>BAJA</h3>
	<p>
		Son eventos que no tienen un impacto directo en el servicio a los clientes y/o no afectan los 
		procesos cr&iacute;ticos internos de la compa&ntilde;&iacute;a, deben ser atendidos en forma r&aacute;pida para que no 
		generen un impacto mayor en el servicio o procesos cr&iacute;ticos internos.</p>
	<p>
		Ejemplos de este tipo son: Falla de tarjeta RP o SNT en un nodo BSC, alarmas por niveles de 
		ruido en tramas de interconexi&oacute;n,  Sistema OSS fuera de servicio, retrasos de minutos de 
		archivos TTFILE</p>
	<H3>MEDIA</H3>
	<p>
		Uno o m&aacute;s eventos en la red o plataformas han fallado o fallan resultando en una p&eacute;rdida menor 
		de servicio a clientes y/o a los procesos cr&iacute;ticos de la compa&ntilde;&iacute;a y para los cuales existen 
		procedimientos de recuperaci&oacute;n o bypass probados. </p>
	<p>
		Ejemplos de este tipo de severidad son: Algunas recargas de IVR afectadas, como es el caso de 
		Ripley, ABC , recargas WAP, alguno de los equipos del servicio Vending afectados en su 
		funcionamiento, una trama de interconexi&oacute;n con otra compa&ntilde;&iacute;a fuera de servicio, corte de 
		energ&iacute;a en un sitio de cobertura &uacute;nica en zona geogr&aacute;fica, afectado por m&aacute;s de 4 horas y sin 
		respaldo.</p>
	<H3>ALTA</H3>
	<p>
		Uno o m&aacute;s eventos en la red o plataformas han fallado o fallan en forma consistente resultando 
		en una p&eacute;rdida de servicio a clientes y/o a los procesos cr&iacute;ticos de la compa&ntilde;&iacute;a. Ejemplos de 
		eventos en este nivel de severidad son los que causan un impacto intermitente en los clientes, 
		p&eacute;rdida de redundancia de elementos cr&iacute;ticos de la red, p&eacute;rdida de la capacidad de diagn&oacute;stico o 
		rutinas operativas de gesti&oacute;n del sistema integrado de la red, el elemento no est&aacute; operando de 
		acuerdo a las especificaciones t&eacute;cnicas resultando en una perdida de servicio parcial a clientes.</p>
	<p>
		Ejemplos concretos son: Reboot de nodos MSC o BSC los que retornan en servicio despu&acute;es del 
		tiempo esperado con todos los sitios asociados, Servicio SMS Intercompa&ntilde;&iacute;a fuera de servicio, 
		Recargas CMR fuera de servicio, Nodo MSC con Interconecci&oacute;n con otra compa&ntilde;&iacute;a fuera de 
		servicio, Sitio de cobertura &uacute;nica en zona geogr&aacute;fica fuera de servicio.</p>
	<h3>CRITICA</h3>
	<p>
		Uno o m&aacute;s elementos cr&iacute;ticos para el servicio del sistema integrado de red no opera causando 
		perdida total o parcial del servicio para un grupo significativo de clientes. Un evento asignado 
		con nivel de severidad cr&iacute;tico, puede cambiar a catastr&oacute;fico en el caso de que no exista soluci&oacute;n 
		en el corto plazo.</p>
	<p>
		Ejemplos de eventos en este nivel de severidad son la p&eacute;rdida de la capacidad 
		de operaci&oacute;n de partes de la red o plataformas causando un impacto en el servicio a los clientes, 
		inestabilidades continuas o frecuentes afectando la capacidad de manejo del tr&aacute;fico, p&eacute;rdida de 
		conectividad o aislamiento de una porci&oacute;n de la red.
		Ejemplos concretos de la red de Entel PCS son: Nodo HLR fuera de servicio, Nodo BSC fuera de 
		servicio en horario de alto tr&aacute;fico,  Enlace Internet fuera de servicio en horario de tr&aacute;fico diurno. 
		Servicio de Interconexi&oacute;n de voz con otras compa&ntilde;&iacute;as m&oacute;viles y fijas afectados en su totalidad</p>
	<h3>CATASTROFICA</h3>
	<p>
		El sistema integrado de red est&aacute; inoperable causando una ca&iacute;da grave o catastr&oacute;fica. Los clientes 
		han experimentado una p&eacute;rdida total del servicio y no se cuenta con mecanismo de recuperaci&oacute;n 
		o bypass disponibles en forma inmediata. </p>
	<p>
		Ejemplos de eventos en este nivel de severidad son 
		una situaci&oacute;n de siniestro en el centro de operaciones del z&oacute;calo de la red, afectando a los nodos 
		de Operaci&oacute;n de la red de Entel PCS, la p&eacute;rdida total de la capacidad de la disponibilidad de la 
		plataforma ORGA y su redundancia. Sin que exista la posibilidad de retorno a operaci&oacute;n en el 
		corto plazo</p>
