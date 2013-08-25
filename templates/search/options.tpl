{* Purpose of this template: Display search options *}
<input type="hidden" id="active_datasheete" name="active[Datasheete]" value="1" checked="checked" />
<div>
    <input type="checkbox" id="active_datasheete_datasheets" name="search_datasheete_types['datasheet']" value="1"{if $active_datasheet} checked="checked"{/if} />
    <label for="active_datasheete_datasheets">{gt text='Datasheets' domain='module_datasheete'}</label>
</div>
