
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">54-ФЗ</label>
    <div class="col-lg-5">
        <label>
          <input class="toggle-checkbox-sm checkbox-receipt" type="checkbox" <?php if($param["receipt"] == 1){ echo 'checked=""'; } ?> name="payment_param[receipt]" value="1" >
          <span><span></span></span>
        </label>
    </div>
  </div>

  <div class="payment-receipt" <?php if(!$param["receipt"]){ echo 'style="display: none;"'; } ?> >

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ставка НДС</label>
    <div class="col-lg-5">
         <select name="payment_param[vat_code]" class="selectpicker" >
            <option value="tax_ru_1" >Не выбрано</option>
            <option value="tax_ru_1" <?php if($param["vat_code"] == "tax_ru_1"){ echo 'selected=""'; } ?> >без НДС</option>
            <option value="tax_ru_2" <?php if($param["vat_code"] == "tax_ru_2"){ echo 'selected=""'; } ?> >НДС по ставке 0%</option>
            <option value="tax_ru_3" <?php if($param["vat_code"] == "tax_ru_3"){ echo 'selected=""'; } ?> >НДС чека по ставке 10%</option>
            <option value="tax_ru_4" <?php if($param["vat_code"] == "tax_ru_4"){ echo 'selected=""'; } ?> >НДС чека по ставке 18%</option>
            <option value="tax_ru_5" <?php if($param["vat_code"] == "tax_ru_5"){ echo 'selected=""'; } ?> >НДС чека по расчетной ставке 10/110</option>
            <option value="tax_ru_6" <?php if($param["vat_code"] == "tax_ru_6"){ echo 'selected=""'; } ?> >НДС чека по расчетной ставке 18/118</option>
            <option value="tax_ru_7" <?php if($param["vat_code"] == "tax_ru_7"){ echo 'selected=""'; } ?> >НДС чека по расчетной ставке 20</option>
            <option value="tax_ru_8" <?php if($param["vat_code"] == "tax_ru_8"){ echo 'selected=""'; } ?> >НДС чека по расчетной ставке 20/120</option>
         </select>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Сумма налога %</label>
    <div class="col-lg-5">
        <input type="text" class="form-control" value="<?php echo $param["amount_tax"]; ?>"  name="payment_param[amount_tax]" >
    </div>
  </div>

  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID merchant </label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["id_merchant"]; ?>"  name="payment_param[id_merchant]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["key"]; ?>"  name="payment_param[key]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Валюта</label>
    <div class="col-lg-5">

       <select name="payment_param[curr]" class="selectpicker" >
         <option <?php if($param["curr"] == "643"){ echo ' selected=""'; } ?> value="643" >Российские рубли</option>
         <option <?php if($param["curr"] == "710"){ echo ' selected=""'; } ?> value="710" >Южно-Африканские ранды</option>
         <option <?php if($param["curr"] == "840"){ echo ' selected=""'; } ?> value="840" >Американские доллары</option>
         <option <?php if($param["curr"] == "978"){ echo ' selected=""'; } ?> value="978" >Евро</option>
         <option <?php if($param["curr"] == "980"){ echo ' selected=""'; } ?> value="980" >Украинские гривны</option>
         <option <?php if($param["curr"] == "398"){ echo ' selected=""'; } ?> value="398" >Казахстанские тенге</option>
         <option <?php if($param["curr"] == "974"){ echo ' selected=""'; } ?> value="974" >Белорусские рубли</option>
         <option <?php if($param["curr"] == "972"){ echo ' selected=""'; } ?> value="972" >Таджикские сомони</option>
         <option <?php if($param["curr"] == "985"){ echo ' selected=""'; } ?> value="985" >Польский злотый</option>
       </select>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при удачной оплате</label>
    <div class="col-lg-5">
         <input type="text" class="form-control"  value="<?php echo $param["link_success"] ? $param["link_success"] : $config["urlPath"] . "/pay/status/success"; ?>" name="payment_param[link_success]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при отмене оплаты</label>
    <div class="col-lg-5">
         <input type="text" class="form-control"  value="<?php echo $param["link_cancel"] ? $param["link_cancel"] : $config["urlPath"] . "/pay/status/fail"; ?>" name="payment_param[link_cancel]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>