<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

//\Bitrix\Main\UI\Extension::load("ui.bootstrap4");
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
$ComponentPath = $this->GetFolder();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

	<div id="vue_formobject" class="my-5 container">
		<div class="row">
			<div class="col-12">
				<?if(!empty($arResult["ERROR_MESSAGE"]))
				{
					foreach($arResult["ERROR_MESSAGE"] as $v)
						ShowError($v);
				}
				if($arResult["OK_MESSAGE"] <> '')
				{
					?><div class="alert alert-success"><?=$arResult["OK_MESSAGE"]?></div><?
				}
				?>
				
				<form action="" method="POST" ENCTYPE="multipart/form-data">
					<?=bitrix_sessid_post()?>
					<div class="form-group mb-3">
						<label for="orderApplication_title" class="h5"><?=GetMessage("FORM_TITLE");?><?
							if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TITLE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-control-required">*</span><?endif;?></label>
						<input
							type="text"
							id="orderApplication_title"
							name="orderApplication_title"
							class="form-control"
							value=""
							<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TITLE", $arParams["REQUIRED_FIELDS"])): ?>required<?endif?>
						/>
					</div>

					<div class="form-group mb-3">
						<label for="orderApplication_cat" class="h5"><?=GetMessage("FORM_CAT")?></label>

						<div class="form-check">
						  <input class="form-check-input" type="radio" name="catRadios" id="catRadios1" value="<?=GetMessage("FORM_CAT_1")?>" checked>
						  <label class="form-check-label" for="catRadios1">
						    <?=GetMessage("FORM_CAT_1")?>
						  </label>
						</div>
						<div class="form-check">
						  <input class="form-check-input" type="radio" name="catRadios" id="catRadios2" value="<?=GetMessage("FORM_CAT_2")?>">
						  <label class="form-check-label" for="catRadios2">
						    <?=GetMessage("FORM_CAT_2")?>
						  </label>
						</div>
					</div>

					<div class="form-group mb-3">
						<label for="orderApplication_type" class="h5"><?=GetMessage("FORM_TYPE")?></label>

						<div class="form-check">
						  <input class="form-check-input" type="radio" name="typeRadios" id="typeRadios1" value="<?=GetMessage("FORM_TYPE_1")?>" checked>
						  <label class="form-check-label" for="typeRadios1">
						    <?=GetMessage("FORM_TYPE_1")?>
						  </label>
						</div>
						<div class="form-check">
						  <input class="form-check-input" type="radio" name="typeRadios" id="typeRadios2" value="<?=GetMessage("FORM_TYPE_2")?>">
						  <label class="form-check-label" for="typeRadios2">
						    <?=GetMessage("FORM_TYPE_2")?>
						  </label>
						</div>
						<div class="form-check">
						  <input class="form-check-input" type="radio" name="typeRadios" id="typeRadios3" value="<?=GetMessage("FORM_TYPE_3")?>">
						  <label class="form-check-label" for="typeRadios3">
						    <?=GetMessage("FORM_TYPE_3")?>
						  </label>
						</div>
					</div>

					<div class="form-group mb-3">
						<label for="orderApplication_store" class="h5"><?=GetMessage("FORM_STORE")?></label>
						<select id="orderApplication_store" name="selected_store" class="form-control">
						  <option><?=GetMessage("FORM_STORE_0")?></option>
						  <option value="<?=GetMessage("FORM_STORE_1")?>"><?=GetMessage("FORM_STORE_1")?></option>
						  <option value="<?=GetMessage("FORM_STORE_2")?>"><?=GetMessage("FORM_STORE_2")?></option>
						  <option value="<?=GetMessage("FORM_STORE_3")?>"><?=GetMessage("FORM_STORE_3")?></option>
						</select>
					</div>

					<div class="form-group mb-3 position-relative">	
						<div class="row">
							<div class="col-12">
								<div class="h5"><?=GetMessage("FORM_ORDER_LIST")?></div>
							</div>
						</div>
						

						<div v-for="i in orders_list_count">

							<div v-if="i>0" class="d-block d-lg-none py-2">
								<hr />
							</div>

							<form_orderlist :list_number="i"></form_orderlist>
							
						</div>

						<div class="count-btns">				
							<span type="button" class="btn btn-primary" @click="upcount">+</span>
							<span type="button" class="btn btn-primary" @click="downcount">-</span>
						</div>

						<input type="hidden" id="order_list_count" name="order_list_count" v-model="num">
					</div>

					<div class="form-group my-3 py-3">
						<input name="orderFile" type="file" class="form-control-file" id="orderFile1">
					</div>


					<div class="form-group mb-3">
						<label for="orderApplication_message" class="h5"><?=GetMessage("FORM_MESSAGE")?></label>
						<textarea class="form-control" id="orderApplication_message" name="MESSAGE" rows="5"><?=$arResult["MESSAGE"]?></textarea>
					</div>		

					<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
					<input type="submit" name="submit"  value="<?=GetMessage("FORM_SUBMIT")?>" class="btn btn-primary">
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript" src="<?=$ComponentPath.'/js/vue_2_7_14.js'?>"></script>
<script type="text/javascript" src="<?=$ComponentPath.'/js/vue_main.js'?>"></script>