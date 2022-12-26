
<h3> Demo Module - Product Custom Fields</h3>
<div class='pcf-component'>
    <div class="pcf-component__column-item">{$pcf.custom_field_a}</div>
    <div class="pcf-component__column-item hide-on-mobile">{$pcf.custom_field_b}</div>
    <div class="pcf-component__column-item hide-on-tablet">{$pcf.custom_field_c}</div>
</div>
<style>
.pcf-component {
    width: 100%;
    display: flex;
    gap: 10px;
}

.pcf-component__column-item {
    display:block;
    padding: 2em;
    border: 1px solid grey;
    flex-grow: 1;
}

@media (max-width: 1024px){
    .hide-on-tablet {
        display:none;
    }
}
@media (max-width: 500px){
    .hide-on-mobile {
        display:none;
    }
}
</style>