
<h3> Demo Module - Product Custom Fields</h3>
<div class='pcf-component'>
    <div class="pcf-component__column-item">{if isset($pcf.custom_field_a)}{$pcf.custom_field_a}{/if}</div>
    <div class="pcf-component__column-item {if $device == 'mobile'} hide {/if}">
        {if isset($pcf.custom_field_b)}{$pcf.custom_field_b}{/if}
    </div>
    <div class="pcf-component__column-item {if $device == 'tablet' || $device == 'mobile'} hide {/if}">
        {if isset($pcf.custom_field_c)}{$pcf.custom_field_c}{/if}
    </div>
</div>