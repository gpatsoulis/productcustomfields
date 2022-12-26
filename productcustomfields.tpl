
<div id="product-custom-field" class="panel product-tab">
    <input type="hidden" name="submitted_tabs[]" value="productcustomfields" />
    <h3 class="tab"><i class="icon-info"></i> {l s='Product Custom Fields'}</h3> 

    <div class="form-group">
        <div class="col-lg-12">
        <label class="control-label col-lg-3" for="product_custom_field_a">
            <span class="label-tooltip" data-toggle="tooltip" title="{l s='Custom string 1'}">
                {l s='Custom string 1'}
            </span>
        </label>
        <div class="col-lg-5">
            <input type="text" id="product_custom_fields" name="product_custom_field_a" value="{$pcf.custom_field_a}"> 
        </div>
        </div>
    </div>
 
    <div class="form-group">
        <div class="col-lg-12">
        <label class="control-label col-lg-3" for="product_custom_field_b">
            <span class="label-tooltip" data-toggle="tooltip" title="{l s='Custom string 2'}">
                {l s='Custom string 2'}
            </span>
        </label>
        <div class="col-lg-5">
            <input type="text" id="product_custom_fields_2" name="product_custom_field_b" value="{$pcf.custom_field_b}"> 
        </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-12">
        <label class="control-label col-lg-3" for="product_custom_field_c">
            <span class="label-tooltip" data-toggle="tooltip" title="{l s='Custom string 3'}">
                {l s='Custom string 3'}
            </span>
        </label>
        <div class="col-lg-5">
            <input type="text" id="product_custom_fields_3" name="product_custom_field_c" value="{$pcf.custom_field_c}"> 
        </div>
        </div>
    </div>
 
    

    <div class="panel-footer">
        <a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}
        {if isset($smarty.request.page) && $smarty.request.page > 1}&amp;submitFilterproduct={$smarty.request.page|intval}{/if}" class="btn btn-default">
            <i class="process-icon-cancel"></i> {l s='Cancel'}</a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i>
            {l s='Save'}</button>
        <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i>
            {l s='Save and stay'}</button>
    </div>

</div>
