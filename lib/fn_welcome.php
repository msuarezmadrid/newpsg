<?php
	session_start();
	require "../autoloader.php";
    use App\Funciones\classFunciones;
	
	use App\Componentes\DependencyContainer;

    $container = new DependencyContainer();
	$cCfn = $container->getFunciones();
	$cCfg = $container->getConfig();
	$cCfnSeg = $container->getFuncionesSeguridad();
	
	$cCfn->checkSession();
	
	include("fn_seguridad.php");
	
	$case=( isset($_REQUEST["case"]) ) ? $_REQUEST["case"] : NULL ;
	
	switch ($case){
		case 1:{
			$sql="SELECT TITULO,TEXTO FROM intradb.PARAM_INFO_MODAL WHERE ACTIVO=1 ";
			$result=$cCfn->exeQuery(null,null,null,$cCfn->getLocal(),$sql,null);
			$row = $result->fetch_assoc();
			$txt_encabezado=$row['TEXTO'];
			$politicas=traePoliticas();
			$texto=$txt_encabezado.$politicas;
			
			$modal="<div class='modal-header'>
				<h4 class='modal-title'>".$row['TITULO']."</h4>
			</div>";
			
			$modal.="<div class='modal-body'> $texto ";
			
			$modal.="
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Cerrar</button>
			</div>";
			
			echo $modal;
			
			break;
		}
	}
?>