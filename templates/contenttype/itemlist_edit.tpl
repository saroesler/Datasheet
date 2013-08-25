{* Purpose of this template: edit view of generic item list content type *}

<div class="z-formrow">
    {gt text='Object type' domain='module_datasheete' assign='objectTypeSelectorLabel'}
    {formlabel for='Datasheete_objecttype' text=$objectTypeSelectorLabel}
    {datasheeteObjectTypeSelector assign='allObjectTypes'}
    {formdropdownlist id='Datasheete_objecttype' dataField='objectType' group='data' mandatory=true items=$allObjectTypes}
    <div class="z-sub z-formnote">{gt text='If you change this please save the element once to reload the parameters below.' domain='module_datasheete'}</div>
</div>

{formvolatile}
{if $properties ne null && is_array($properties)}
    {nocache}
    {foreach key='registryId' item='registryCid' from=$registries}
        {assign var='propName' value=''}
        {foreach key='propertyName' item='propertyId' from=$properties}
            {if $propertyId eq $registryId}
                {assign var='propName' value=$propertyName}
            {/if}
        {/foreach}
        <div class="z-formrow">
            {modapifunc modname='Datasheete' type='category' func='hasMultipleSelection' ot=$objectType registry=$propertyName assign='hasMultiSelection'}
            {gt text='Category' domain='module_datasheete' assign='categorySelectorLabel'}
            {assign var='selectionMode' value='single'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' domain='module_datasheete' assign='categorySelectorLabel'}
                {assign var='selectionMode' value='multiple'}
            {/if}
            {formlabel for="Datasheete_catids`$propertyName`" text=$categorySelectorLabel}
            {formdropdownlist id="Datasheete_catids`$propName`" items=$categories.$propName dataField="catids`$propName`" group='data' selectionMode=$selectionMode}
            <div class="z-sub z-formnote">{gt text='This is an optional filter.' domain='module_datasheete'}</div>
        </div>
    {/foreach}
    {/nocache}
{/if}
{/formvolatile}

<div class="z-formrow">
    {gt text='Sorting' domain='module_datasheete' assign='sortingLabel'}
    {formlabel text=$sortingLabel}
    <div>
        {formradiobutton id='Datasheete_srandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='module_datasheete' assign='sortingRandomLabel'}
        {formlabel for='Datasheete_srandom' text=$sortingRandomLabel}
        {formradiobutton id='Datasheete_snewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='module_datasheete' assign='sortingNewestLabel'}
        {formlabel for='Datasheete_snewest' text=$sortingNewestLabel}
        {formradiobutton id='Datasheete_sdefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='module_datasheete' assign='sortingDefaultLabel'}
        {formlabel for='Datasheete_sdefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="z-formrow">
    {gt text='Amount' domain='module_datasheete' assign='amountLabel'}
    {formlabel for='Datasheete_amount' text=$amountLabel}
    {formintinput id='Datasheete_amount' dataField='amount' group='data' mandatory=true maxLength=2}
</div>

<div class="z-formrow">
    {gt text='Template' domain='module_datasheete' assign='templateLabel'}
    {formlabel for='Datasheete_template' text=$templateLabel}
    {datasheeteTemplateSelector assign='allTemplates'}
    {formdropdownlist id='Datasheete_template' dataField='template' group='data' mandatory=true items=$allTemplates}
</div>

<div id="customtemplatearea" class="z-formrow z-hide">
    {gt text='Custom template' domain='module_datasheete' assign='customTemplateLabel'}
    {formlabel for='Datasheete_customtemplate' text=$customTemplateLabel}
    {formtextinput id='Datasheete_customtemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80}
    <div class="z-sub z-formnote">{gt text='Example' domain='module_datasheete'}: <em>itemlist_[objecttype]_display.tpl</em></div>
</div>

<div class="z-formrow z-hide">
    {gt text='Filter (expert option)' domain='module_datasheete' assign='filterLabel'}
    {formlabel for='Datasheete_filter' text=$filterLabel}
    {formtextinput id='Datasheete_filter' dataField='filter' group='data' mandatory=false maxLength=255}
    <div class="z-sub z-formnote">({gt text='Syntax examples' domain='module_datasheete'}: <kbd>name:like:foobar</kbd> {gt text='or' domain='module_datasheete'} <kbd>status:ne:3</kbd>)</div>
</div>

{pageaddvar name='javascript' value='prototype'}
<script type="text/javascript">
/* <![CDATA[ */
    function datashToggleCustomTemplate() {
        if ($F('Datasheete_template') == 'custom') {
            $('customtemplatearea').removeClassName('z-hide');
        } else {
            $('customtemplatearea').addClassName('z-hide');
        }
    }

    document.observe('dom:loaded', function() {
        datashToggleCustomTemplate();
        $('Datasheete_template').observe('change', function(e) {
            datashToggleCustomTemplate();
        });
    });
/* ]]> */
</script>
