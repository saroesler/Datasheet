{* purpose of this template: build the Form to edit an instance of datasheet *}
{include file='admin/header.tpl'}
{pageaddvar name='javascript' value='modules/Datasheete/javascript/Datasheete_editFunctions.js'}
{pageaddvar name='javascript' value='modules/Datasheete/javascript/Datasheete_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit datasheet' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{elseif $mode eq 'create'}
    {gt text='Create datasheet' assign='templateTitle'}
    {assign var='adminPageIcon' value='new'}
{else}
    {gt text='Edit datasheet' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{/if}
<div class="datasheete-datasheet datasheete-edit">
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-admin-content-pagetitle">
        {icon type=$adminPageIcon size='small' alt=$templateTitle}
        <h3>{$templateTitle}</h3>
    </div>
{form enctype='multipart/form-data' cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {datasheeteFormFrame}

    {formsetinitialfocus inputId='name'}


    <fieldset>
        <legend>{gt text='Content'}</legend>
        
        <div class="z-formrow">
            {formlabel for='name' __text='Name' mandatorysym='1'}
            {formtextinput group='datasheet' id='name' mandatory=true readOnly=false __title='Enter the name of the datasheet' textMode='singleline' maxLength=255 cssClass='required' }
            {datasheeteValidationError id='name' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='kind' __text='Kind' mandatorysym='1'}
            {formdropdownlist group='datasheet' id='kind' mandatory=true __title='Choose the kind' selectionMode='single'}
        </div>
        
        <div class="z-formrow">
            {assign var='mandatorySym' value='1'}
            {if $mode ne 'create'}
                {assign var='mandatorySym' value='0'}
            {/if}
            {formlabel for='datasheet' __text='Datasheet' mandatorysym=$mandatorySym}<br />{* break required for Google Chrome *}
            {if $mode eq 'create'}
                {formuploadinput group='datasheet' id='datasheet' mandatory=true readOnly=false cssClass='required validate-upload' }
            {else}
                {formuploadinput group='datasheet' id='datasheet' mandatory=false readOnly=false cssClass=' validate-upload' }
                <p class="z-formnote"><a id="resetDatasheetVal" href="javascript:void(0);" class="z-hide">{gt text='Reset to empty value'}</a></p>
            {/if}
            
                <div class="z-formnote">{gt text='Allowed file extensions:'} <span id="fileextensionsdatasheet">gif, jpeg, jpg, png, pdf, doc, docx, odt, xls, ods</span></div>
            {if $mode ne 'create'}
                {if $datasheet.datasheet ne ''}
                    <div class="z-formnote">
                        {gt text='Current file'}:
                        <a href="{$datasheet.datasheetFullPathUrl}" title="{$datasheet.name|replace:"\"":""}"{if $datasheet.datasheetMeta.isImage} rel="imageviewer[datasheet]"{/if}>
                        {if $datasheet.datasheetMeta.isImage}
                            {thumb image=$datasheet.datasheetFullPath objectid="datasheet-`$datasheet.id`" manager=$datasheetThumbManagerDatasheet tag=true img_alt=$datasheet.name}
                        {else}
                            {gt text='Download'} ({$datasheet.datasheetMeta.size|datasheeteGetFileSize:$datasheet.datasheetFullPath:false:false})
                        {/if}
                        </a>
                    </div>
                {/if}
            {/if}
            {datasheeteValidationError id='datasheet' class='required'}
            {datasheeteValidationError id='datasheet' class='validate-upload'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='description' __text='Description' mandatorysym='1'}
            {formtextinput group='datasheet' id='description' mandatory=true __title='Enter the description of the datasheet' textMode='multiline' rows='6' cols='50' cssClass='required' }
            {datasheeteValidationError id='description' class='required'}
        </div>
    </fieldset>
    
    {if $mode ne 'create'}
        {include file='admin/include_standardfields_edit.tpl' obj=$datasheet}
    {/if}
    
    {* include display hooks *}
    {assign var='hookid' value=null}
    {if $mode ne 'create'}
        {assign var='hookid' value=$datasheet.id}
    {/if}
    {notifydisplayhooks eventname='datasheete.ui_hooks.datasheets.form_edit' id=$hookId assign='hooks'}
    {if is_array($hooks) && count($hooks)}
        {foreach key='providerArea' item='hook' from=$hooks}
            <fieldset>
                {$hook}
            </fieldset>
        {/foreach}
    {/if}
    
    {* include return control *}
    {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="z-formrow">
                {formlabel for='repeatcreation' __text='Create another item after save'}
                {formcheckbox group='datasheet' id='repeatcreation' readOnly=false}
            </div>
        </fieldset>
    {/if}
    
    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {foreach item='action' from=$actions}
        {assign var='actionIdCapital' value=$action.id|@ucwords}
        {gt text=$action.title assign='actionTitle'}
        {*gt text=$action.description assign='actionDescription'*}{* TODO: formbutton could support title attributes *}
        {if $action.id eq 'delete'}
            {gt text='Really delete this datasheet?' assign='deleteConfirmMsg'}
            {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
        {else}
            {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
        {/if}
    {/foreach}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
    {/datasheeteFormFrame}
{/form}

</div>
{include file='admin/footer.tpl'}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='deleteImageArray'}


<script type="text/javascript">
/* <![CDATA[ */

    var formButtons, formValidator;

    function handleFormButton (event) {
        var result = formValidator.validate();
        if (!result) {
            // validation error, abort form submit
            Event.stop(event);
        } else {
            // hide form buttons to prevent double submits by accident
            formButtons.each(function (btn) {
                btn.addClassName('z-hide');
            });
        }

        return result;
    }

    document.observe('dom:loaded', function() {

        datashAddCommonValidationRules('datasheet', '{{if $mode ne 'create'}}{{$datasheet.id}}{{/if}}');
        {{* observe validation on button events instead of form submit to exclude the cancel command *}}
        formValidator = new Validation('{{$__formid}}', {onSubmit: false, immediate: true, focusOnError: false});
        {{if $mode ne 'create'}}
            var result = formValidator.validate();
        {{/if}}

        formButtons = $('{{$__formid}}').select('div.z-formbuttons input');

        formButtons.each(function (elem) {
            if (elem.id != 'btnCancel') {
                elem.observe('click', handleFormButton);
            }
        });

        Zikula.UI.Tooltips($$('.datasheeteFormTooltips'));
        datashInitUploadField('datasheet');
    });

/* ]]> */
</script>
