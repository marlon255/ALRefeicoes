<?php
	include('../include/topo.php');
	include('../include/corpo.php');
?>
<div class="conteudo">
	<h2>Lançamento Fornecedor Local</h2>
	<div>
		<div class="botao_visualizar" id="lancamentos_local">Verificar Lançamentos</div>
	</div>
	<div id="conteudo_modal">
		<div class="cabecalho_modal">
			<div class="cabecalho">Lançamentos de Fornecedor Local</div>
			<div class="close">X</div>
		</div>
		<div class="ativo_users">
			<div class="cabecalho_atualizar" style="width: 945px;">
				<div class="div_cabecalho_atualizar_especial"><span class="text_menor">Data da Compra</span></div>
				<div class="div_cabecalho_atualizar">Unidade de  Produção</div>
				<div class="div_cabecalho_atualizar">Fornecedor</div>
				<div class="div_cabecalho_atualizar">Produto</div>
				<div class="div_cabecalho_atualizar_especial">Valor Total</div>
				<div class="div_cabecalho_atualizar_acao">Pago?</div>
				<div class="div_cabecalho_atualizar_acao">Ação</div>
			</div>
			<?php
				if($fetch_LL > 0){
					do{
						if(isset($_POST['atualizar_LL'.$fetch_LL['id']])){
							$new_pago = $_POST['LL_pago_'.$fetch_LL['id']];

							$sql_LL = "UPDATE `fornecimento_local` SET `pago` = :new_pago WHERE id = '".$_POST['LL_id']."'";
							$LL_pago = $PDO->prepare($sql_LL);
							$LL_pago->bindValue(":new_pago", $new_pago);
							$LL_pago->execute();
							echo "<script>alert('Dados atualizados com Sucesso!')</script>";
							echo "<script>location.href='fornecedor_local.php';</script>";
						}
						if(isset($_POST['excluir_LL'.$fetch_LL['id']])){
							$sql_del_LL = "DELETE FROM `fornecimento_local` WHERE `id` = '".$_POST['LL_id']."'";
							$del_LL = $PDO->prepare($sql_del_LL);
							$del_LL->execute();
							echo "<script>alert('Dados excluidos com Sucesso!')</script>";
							echo "<script>location.href='fornecedor_local.php';</script>";
						}
			?>
			<form method="post" style="width: 945px;">
				<input type="hidden" name="LL_id" value="<?=$fetch_LL['id'];?>">
				<div class="infor_atualizar_especial"><?=date('d/m/Y',strtotime($fetch_LL['data_compra']));?></div>
				<div class="infor_atualizar"><?=$fetch_LL['unidade_producao'];?></div>
				<div class="infor_atualizar"><?=$fetch_LL['fornecedor_local'];?></div>
				<div class="infor_atualizar"><?=$fetch_LL['produto_local'];?></div>
				<div class="infor_atualizar_especial"><?=str_replace(".", ",", $fetch_LL['total_produto']);?></div>
				<select name="LL_pago_<?=$fetch_LL['id'];?>">
				<?php
					if($fetch_LL['pago'] == 1){		
				?>
					<option value="1" selected>Sim</option>
					<option value="2">Não</option>
				<?php
					}else{
				?>
					<option value="1">Sim</option>
					<option value="2" selected>Não</option>
				<?php
					}
				?>
					
				</select>
				<div class="botoes_acoes">
					<input type="submit" class="botao_editar" name="atualizar_LL<?=$fetch_LL['id'];?>" value title="Editar">
					<input type="submit" class="botao_excluir" name="excluir_LL<?=$fetch_LL['id'];?>" value title="Excluir">
				</div>
			</form>
			<?php
				}while($fetch_LL = $exibir_LL->fetch(PDO::FETCH_ASSOC));
			}else{
				echo "Nenhum lançamento realizado!";
			}
			?>
		</div>
	</div>
	<form method="post" class="form_producao">
		<div class="inputs">
			<!-- Buscando a unidade de produção -->
			<div class="label_input">
				<label>Unidade de Produção</label>
				<select name="unidade_producao_local">
					<option selected disabled>Selecione --></option>
					<?php
				if($fetch_unidade_ativo > 0):
					do{
				?>
				<option><?=$fetch_unidade_ativo['unidade'];?></option>
				<?php
				}while($fetch_unidade_ativo = $exibir_unidade_ativo->fetch(PDO::FETCH_ASSOC));
				endif;
				?>
				</select>
			</div>
			<!-- Data da compra do produto -->
			<div class="label_input">
				<label>Data da Compra</label>
				<input type="date" name="data_compra_local">
			</div>
			<!-- Buscando o produto -->
			<div class="label_input">
				<label>Fornecedor</label>
				<select name="fornecedor_local" id="fornecedor_local" onblur="getDados()">
					<option selected disabled>Selecione --></option>
					<?php
				if($fetch_fornecedor_local > 0):
					do{
				?>
				<option><?=$fetch_fornecedor_local['nome_fantasia'];?></option>
				<?php
				}while($fetch_fornecedor_local = $exibir_fornecedor_local->fetch(PDO::FETCH_ASSOC));
				endif;
				?>
				</select>
			</div>
			<!-- Buscando o produto -->
			<div class="label_input">
				<label>Produto</label>
				<select name="produto_local" id="produto_local" onblur="dados()">
					<option selected disabled>Selecione --></option>
				</select>
				<input type="hidden" name="pergunta_combustivel" id="pergunta_combustivel">
			</div>
			<!-- Quantidade a ser comprada -->
			<div class="label_input">
				<label>Quantidade</label>
				<input type="number" name="quantidade_local" id="quantidade_local">
				<input type="hidden" name="valor_produto" id="valor_produto">
			</div>
			<!-- Div que será mostrado caso seja combustivel -->
			<div id="combustivel" style="display: none;">
				<div class="label_input">
					<label>Motorista</label>
					<input type="text" name="motorista_combustivel" id="focusaki">
				</div>
				<div class="label_input">
					<label>Placa do Veículo</label>
					<input type="text" name="veiculo_combustivel" id="veiculo_combustivel" onblur="km()">
				</div>
				<div class="label_input">
					<label>KM do Veículo</label>
					<input type="number" name="km_combustivel" id="km_combustivel">
				</div>
					<input type="hidden" name="km_antigo" id="km_antigo">
					<input type="hidden" name="consumo_combustivel" id="consumo_combustivel" readonly>
			</div>
		</div>
		<!-- Botao -->
			<input type="submit" class="buton_submit" name="cadastro_local">
	</form>
</div>
<?php
	include('../include/rodape.php');
?>