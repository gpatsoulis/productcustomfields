
{if isset($pcf)}
<h3> Demo Module - Product Custom Fields</h3>
<div class='pcf-component'>
    {if isset($pcf->custom_field_a) && $pcf->custom_field_a != ''}
        <div class="pcf-component__column-item">
            {$pcf->custom_field_a}
        </div>
    {/if}
    {if isset($pcf->custom_field_b) && $pcf->custom_field_b != ''}
        <div class="pcf-component__column-item {if $device == 'mobile'} hide {/if}">
            {$pcf->custom_field_b}
        </div>
    {/if}
    {if isset($pcf->custom_field_c) && $pcf->custom_field_c != ''}
        <div class="pcf-component__column-item {if $device == 'tablet' || $device == 'mobile'} hide {/if}">
            {$pcf->custom_field_c}
        </div>
    {/if}
</div>
{else}<!-- Product Custom Fields Module --- no fields available for product -->{/if}