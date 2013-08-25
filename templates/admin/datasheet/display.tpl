{* purpose of this template: datasheets display view in admin area *}
{include file='admin/header.tpl'}
<div class="datasheete-datasheet datasheete-display">
{gt text='Datasheet' assign='templateTitle'}
{assign var='templateTitle' value=$datasheet.name|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-admin-content-pagetitle">
    {icon type='display' size='small' __alt='Details'}
    <h3>{$templateTitle|notifyfilters:'datasheete.filter_hooks.datasheets.filter'} ({$datasheet.workflowState|datasheeteObjectState:false|lower}){icon id='itemactionstrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h3>
</div>


<dl>
    <dt>{gt text='Kind'}</dt>
    <dd>{$datasheet.kind|datasheeteGetListEntry:'datasheet':'kind'|safetext}</dd>
    <dt>{gt text='Datasheet'}</dt>
    <dd>  <a href="{$datasheet.datasheetFullPathURL}" title="{$datasheet.name|replace:"\"":""}"{if $datasheet.datasheetMeta.isImage} rel="imageviewer[datasheet]"{/if}>
      {if $datasheet.datasheetMeta.isImage}
          {thumb image=$datasheet.datasheetFullPath objectid="datasheet-`$datasheet.id`" manager=$datasheetThumbManagerDatasheet tag=true img_alt=$datasheet.name}
      {else}
          {gt text='Download'} ({$datasheet.datasheetMeta.size|datasheeteGetFileSize:$datasheet.datasheetFullPath:false:false})
      {/if}
      </a>
    </dd>
    <dt>{gt text='Description'}</dt>
    <dd>{$datasheet.description}</dd>
    
</dl>
{include file='admin/include_standardfields_display.tpl' obj=$datasheet}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    {* include display hooks *}
    {notifydisplayhooks eventname='datasheete.ui_hooks.datasheets.display_view' id=$datasheet.id urlobject=$currentUrlObject assign='hooks'}
    {foreach key='providerArea' item='hook' from=$hooks}
        {$hook}
    {/foreach}
    {if count($datasheet._actions) gt 0}
        <p id="itemactions">
        {foreach item='option' from=$datasheet._actions}
            <a href="{$option.url.type|datasheeteActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
        {/foreach}
        </p>
        <script type="text/javascript">
        /* <![CDATA[ */
            document.observe('dom:loaded', function() {
                datashInitItemActions('datasheet', 'display', 'itemactions');
            });
        /* ]]> */
        </script>
    {/if}
{/if}

</div>
{include file='admin/footer.tpl'}

