<?php
	include('../include/topo.php');
	include('../include/corpo.php');
?>
<div class="conteudo">
	<h2>Lançamento Fornecedor Externo</h2>
	<div>
		<div class="botao_visualizar" id="lancamentos_externo">Verificar Lançamentos</div>
	</div>
	<div id="conteudo_modal">
		<div class="cabecalho_modal">
			<div class="cabecalho">Lançamentos de Fornecedor Externo</div>
			<div class="close">X</div>
		</div>
		<div class="ativo_users">
			<div class="cabecalho_atualizar" style="width: 948px;">
				<div class="div_cabecalho_atualizar_especial">Emissão</div>
				<div class="div_cabecalho_atualizar">Unidade de Produção</div>
				<div class="div_cabecalho_atualizar">Fornecedor</div>
				<div class="div_cabecalho_atualizar_especial">Forma</div>
				<div class="div_cabecalho_atualizar_especial">Parcela</div>
				<div class="div_cabecalho_atualizar_especial">Valor Total</div>
				<div class="div_cabecalho_atualizar_acao">Pago?</div>
				<div class="div_cabecalho_atualizar_acao">Ação</div>
			</div>
			<?php
				if($fetch_LE > 0){
					do{
						if(isset($_POST['atualizar_LE'.$fetch_LE['id']])){
							$new_pago = $_POST['LE_pago_'.$fetch_LE['id']];

							$sql_LE = "UPDATE `fornecimento_externo` SET `pago` = :new_pago WHERE id = '".$_POST['LE_id']."'";
							$LE_pago = $PDO->prepare($sql_LE);
							$LE_pago->bindValue(":new_pago", $new_pago);
							$LE_pago->execute();
							echo "<script>alert('Dados atualizados com Sucesso!')</script>";
							echo "<script>location.href='fornecedor_externo.php';</script>";
						}
						if(isset($_POST['excluir_LE'.$fetch_LE['id']])){
							$sql_del_LE = "DELETE FROM `fornecimento_externo` WHERE `id` = '".$_POST['LE_id']."'";
							$del_LE = $PDO->prepare($sql_del_LE);
							$del_LE->execute();
							echo "<script>alert('Dados excluidos com Sucesso!')</script>";
							echo "<script>location.href='fornecedor_externo.php';</script>";
						}
			?>
			<form method="post" style="width: 948px;">
				<input type="hidden" name="LE_id" value="<?=$fetch_LE['id'];?>">
				<div class="infor_atualizar_especial"><?=date('d/m/Y',strtotime($fetch_LE['data_emissao']));?></div>
				<div class="infor_atualizar"><?=$fetch_LE['unidade_producao'];?></div>
				<div class="infor_atualizar"><?=$fetch_LE['fornecedor_externo'];?></div>
				<div class="infor_atualizar_especial">
					<?php 
						if($fetch_LE['forma_pagamento'] == "antecipado"){
							echo "Antecipado";
						}elseif($fetch_LE['forma_pagamento'] == "avista"){
							echo "À Vista";
						}elseif($fetch_LE['forma_pagamento'] == "aprazo"){
							echo "A Prazo";
						}else{
							echo "Não encontrado";
						};
					?>
				</div>
				<div class="infor_atualizar_especial">
					<?php
						if($fetch_LE['parcela'] != 0){
							echo $fetch_LE['parcela'];
						}else{
							echo "-";
						}
					?>
				</div>
				<div class="infor_atualizar_especial">
				<?php 
					if($fetch_LE['parcela'] != 0){
						echo str_replace(".", ",", $fetch_LE['valor_unico']);
					}else{
						echo str_replace(".", ",", $fetch_LE['valor_total']);
					};?>
				</div>
				<select name="LE_pago_<?=$fetch_LE['id'];?>">
				<?php
					if($fetch_LE['pago'] == 1){		
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
					<input type="submit" class="botao_editar" name="atualizar_LE<?=$fetch_LE['id'];?>" value title="Editar">
					<input type="submit" class="botao_excluir" name="excluir_LE<?=$fetch_LE['id'];?>" value title="Excluir">
				</div>
			</form>
			<?php
				}while($fetch_LE = $exibir_LE->fetch(PDO::FETCH_ASSOC));
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
				<select name="unidade_producao_externo">
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
		<!-- Número da Nota Fiscal a ser lançada -->
			<div class="label_input">
				<label>Nota Fiscal</label>
				<input type="number" name="nota_fiscal_externo">
			</div>
		<!-- Data de Emissão da Nota fiscal -->
			<div class="label_input">
				<label>Data de Emissão</label>
				<input type="date" name="data_emissao_externo">
			</div>
		<!-- Fornecedor da Nota Fiscal -->
			<div class="label_input">
				<label>Fornecedor</label>
				<select name="fornecedor_externo">
				<option selected disabled>Selecione --></option>
				<?php
				if($fetch_fornecedor_externo > 0):
				do{
				?>
				<option><?=$fetch_fornecedor_externo['nome_fantasia'];?></option>
				<?php
				}while($fetch_fornecedor_externo = $exibir_fornecedor_externo->fetch(PDO::FETCH_ASSOC));
				endif;
				?>
				</select>
			</div>
		<!-- Valor total da nota fiscal -->
			<div class="label_input">
				<label>Valor Total</label>
				<input type="text" name="valor_total_externo" id="money" value="R$0,00">
			</div>
		<!-- Forma de Pagamento -->
			<div class="label_input" style="height: 70px;">
				<label>Forma de Pagamento</label>
				<div class="input_radio">
						Antecipado <input type="radio" name="forma_pagamento" id="antecipado" value="antecipado"><br>
						À Vista <input type="radio" name="forma_pagamento" id="avista" value="avista"><br>
						A Prazo <input type="radio" name="forma_pagamento" id="aprazo" value="aprazo">
				</div>
			</div>
		<!-- Div com todas as opçoes de antecipado -->
			<div class="antecipado">
				<div class="label_input" style="height: 70px;">
					<label>Antecipado:</label>
					<div class="input_radio">
							Deposito em CC <input type="radio" name="forma_antecipado" value="Deposito"><br>
							Boleto Bancario <input type="radio" name="forma_antecipado" value="Boleto"><br>
							Cheque <input type="radio" name="forma_antecipado" value="Cheque">
					</div>
				</div>
				<div class="label_input" id="data_pagamento_antecipado" style="display: none;">
					<label>Data de Pagamento</label>
					<input type="date" name="data_pagamento_antecipado">
				</div>
			</div>
		<!-- Div com todas as opçoes de antecipado -->
			<div class="avista">
				<div class="label_input" style="height: 70px;">
					<label>À Vista:</label>
					<div class="input_radio">
							Deposito em CC <input type="radio" name="forma_avista" value="Deposito"><br>
							Boleto Bancario <input type="radio" name="forma_avista" value="Boleto"><br>
							Cheque <input type="radio" name="forma_avista" value="Cheque">
					</div>
				</div>
				<div class="label_input" id="data_pagamento_avista" style="display: none;">
					<label>Data de Pagamento</label>
					<input type="date" name="data_pagamento_avista"">
				</div>
			</div>
		<!-- Div com todas as opçoes de A Prazo -->
			<div class="aprazo">
			<!-- Quantidade de parcelas -->
					<div class="label_input" id="opcoes_none" style="height: 80px;">
						<label>Quantidade de Parcela</label>
						<div class="input_radio">
							<div>
							1 <input type="radio" name="quantidade_parcela" id="um" value="1">
							</div>
							<div>
							2 <input type="radio" name="quantidade_parcela" id="dois" value="2">
							</div>
							<div>
							3 <input type="radio" name="quantidade_parcela" id="tres" value="3">
							</div>
							<div>
							4 <input type="radio" name="quantidade_parcela" id="quatro" value="4">
							</div>
							<div>
							5 <input type="radio" name="quantidade_parcela" id="cinco" value="5">
							</div>
							<div>
							6 <input type="radio" name="quantidade_parcela" id="seis" value="6">
							</div>
						</div>
					</div>
					<div class="parcela">
						<!-- Div para caso seja somente 1 parcela -->
						<div id="parcela_unica">
						<!-- Data da primeira Parcela -->
							<div class="label_input">
								<label>Data da Parcela Única</label>
								<input type="date" name="data_parcela_unica">
							</div>
						</div>
						<!-- Div para puxar caso selecionado 1 parcela -->
						<div id="primeira_parcela">
						<!-- Data da primeira Parcela -->
							<div class="label_input">
								<label>Data do 1º Vencimento</label>
								<input type="date" name="primeiro_vencimento_externo">
							</div>
							<!-- Valor da 1ª Parcela -->
							<div class="label_input">
								<label>Valor do 1º Vencimento</label>
								<input type="text" name="valor_primeiro_externo" id="money1" value="R$0,00">
							</div>
						</div>

						<!-- Div para puxar caso selecionado 2 parcela -->
						<div id="segunda_parcela">
						<!-- Data da segunda Parcela -->
							<div class="label_input">
								<label>Data do 2º Vencimento</label>
								<input type="date" name="segundo_vencimento_externo">
							</div>
							<!-- Valor da 2ª Parcela -->
							<div class="label_input">
								<label>Valor do 2º Vencimento</label>
								<input type="text" name="valor_segundo_externo" id="money2" value="R$0,00">
							</div>
						</div>

						<!-- Div para puxar caso selecionado 3 parcela -->
						<div id="terceira_parcela">
						<!-- Data da terceira Parcela -->
							<div class="label_input">
								<label>Data do 3º Vencimento</label>
								<input type="date" name="terceiro_vencimento_externo">
							</div>
							<!-- Valor da 3ª Parcela -->
							<div class="label_input">
								<label>Valor do 3º Vencimento</label>
								<input type="text" name="valor_terceiro_externo" id="money3" value="R$0,00">
							</div>
						</div>

						<!-- Div para puxar caso selecionado 4 parcela -->
						<div id="quarta_parcela">
						<!-- Data da quarta Parcela -->
							<div class="label_input">
								<label>Data do 4º Vencimento</label>
								<input type="date" name="quarto_vencimento_externo">
							</div>
							<!-- Valor da 4ª Parcela -->
							<div class="label_input">
								<label>Valor do 4º Vencimento</label>
								<input type="text" name="valor_quarto_externo" id="money4" value="R$0,00">
							</div>
						</div>

						<!-- Div para puxar caso selecionado 5 parcela -->
						<div id="quinta_parcela">
						<!-- Data da quinta Parcela -->
							<div class="label_input">
								<label>Data do 5º Vencimento</label>
								<input type="date" name="quinto_vencimento_externo">
							</div>
							<!-- Valor da 5ª Parcela -->
							<div class="label_input">
								<label>Valor do 5º Vencimento</label>
								<input type="text" name="valor_quinto_externo" id="money5" value="R$0,00">
							</div>
						</div>

						<!-- Div para puxar caso selecionado 6 parcela -->
						<div id="sexta_parcela">
						<!-- Data da sexta Parcela -->
							<div class="label_input">
								<label>Data do 6º Vencimento</label>
								<input type="date" name="sexto_vencimento_externo">
							</div>
							<!-- Valor da 6ª Parcela -->
							<div class="label_input">
								<label>Valor do 6º Vencimento</label>
								<input type="text" name="valor_sexto_externo" id="money6" value="R$0,00">
							</div>
						</div>
					</div>
				</div>
		</div>
		<!-- Botao -->
			<input type="submit" class="buton_submit" name="cadastro_externo">
	</form>
</div>
<?php
	include('../include/rodape.php');
?>