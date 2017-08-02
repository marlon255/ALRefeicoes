<?php
	header("Content-Type: text/html; charset=utf-8", true);
	include('connect.php');
	if(isset($_GET['produto'])){
		$busca_valor = $_GET['produto'];
		if(empty($busca_valor) || $busca_valor == null || $busca_valor == "Selecione -->"){
			echo "<option selected disabled>Selecione --></option>";
		}else{
			$sql = "SELECT * FROM `produto` WHERE descricao = '".$busca_valor."'";
			$query = $PDO->prepare($sql);
			$query->execute();
			$rows = $query->fetch(PDO::FETCH_ASSOC);
			echo $rows['preco_unitario'];
		}
	}
?>