{* purpose of this template: datasheets view view in admin area *}
{include file='admin/header.tpl'}
<div class="datasheete-datasheet datasheete-view">
{gt text='Datasheet list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='view' size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>

{checkpermissionblock component='Datasheete:Datasheet:' instance='::' level='ACCESS_EDIT'}
    {gt text='Create datasheet' assign='createTitle'}
    <a href="{modurl modname='Datasheete' type='admin' func='edit' ot='datasheet'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
{/checkpermissionblock}
{assign var='own' value=0}
{if isset($showOwnEntries) && $showOwnEntries eq 1}
    {assign var='own' value=1}
{/if}
{assign var='all' value=0}
{if isset($showAllEntries) && $showAllEntries eq 1}
    {gt text='Back to paginated view' assign='linkTitle'}
    <a href="{modurl modname='Datasheete' type='admin' func='view' ot='datasheet'}" title="{$linkTitle}" class="z-icon-es-view">
        {$linkTitle}
    </a>
    {assign var='all' value=1}
{else}
    {gt text='Show all entries' assign='linkTitle'}
    <a href="{modurl modname='Datasheete' type='admin' func='view' ot='datasheet' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
{/if}

{include file='admin/datasheet/view_quickNav.tpl'}{* see template file for available options *}

<form class="z-form" id="datasheets_view" action="{modurl modname='Datasheete' type='admin' func='handleselectedentries'}" method="post">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="ot" value="datasheet" />
        <table class="z-datatable">
            <colgroup>
                <col id="cselect" />
                <col id="cworkflowstate" />
                <col id="cname" />
                <col id="ckind" />
                <col id="cdatasheet" />
                <col id="cdescription" />
                <col id="citemactions" />
            </colgroup>
            <thead>
            <tr>
                <th id="hselect" scope="col" align="center" valign="middle">
                    <input type="checkbox" id="toggle_datasheets" />
                </th>
                <th id="hworkflowstate" scope="col" class="z-left">
                    {sortlink __linktext='State' currentsort=$sort modname='Datasheete' type='admin' func='view' ot='datasheet' sort='workflowState' sortdir=$sdir all=$all own=$own workflowState=$workflowState kind=$kind searchterm=$searchterm pageSize=$pageSize}
                </th>
                <th id="hname" scope="col" class="z-left">
                    {sortlink __linktext='Name' currentsort=$sort modname='Datasheete' type='admin' func='view' ot='datasheet' sort='name' sortdir=$sdir all=$all own=$own workflowState=$workflowState kind=$kind searchterm=$searchterm pageSize=$pageSize}
                </th>
                <th id="hkind" scope="col" class="z-left">
                    {sortlink __linktext='Kind' currentsort=$sort modname='Datasheete' type='admin' func='view' ot='datasheet' sort='kind' sortdir=$sdir all=$all own=$own workflowState=$workflowState kind=$kind searchterm=$searchterm pageSize=$pageSize}
                </th>
                <th id="hdatasheet" scope="col" class="z-left">
                    {sortlink __linktext='Datasheet' currentsort=$sort modname='Datasheete' type='admin' func='view' ot='datasheet' sort='datasheet' sortdir=$sdir all=$all own=$own workflowState=$workflowState kind=$kind searchterm=$searchterm pageSize=$pageSize}
                </th>
                <th id="hdescription" scope="col" class="z-left">
                    {sortlink __linktext='Description' currentsort=$sort modname='Datasheete' type='admin' func='view' ot='datasheet' sort='description' sortdir=$sdir all=$all own=$own workflowState=$workflowState kind=$kind searchterm=$searchterm pageSize=$pageSize}
                </th>
                <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
            </tr>
            </thead>
            <tbody>
        
        {foreach item='datasheet' from=$items}
            <tr class="{cycle values='z-odd, z-even'}">
                <td headers="hselect" align="center" valign="top">
                    <input type="checkbox" name="items[]" value="{$datasheet.id}" class="datasheets_checkbox" />
                </td>
                <td headers="hworkflowstate" class="z-left z-nowrap">
                    {$datasheet.workflowState|datasheeteObjectState}
                </td>
                <td headers="hname" class="z-left">
                    <a href="{modurl modname='Datasheete' type='admin' func='display' ot='datasheet' id=$datasheet.id}" title="{gt text='View detail page'}">{$datasheet.name|notifyfilters:'datasheete.filterhook.datasheets'}</a>
                </td>
                <td headers="hkind" class="z-left">
                    {$datasheet.kind|datasheeteGetListEntry:'datasheet':'kind'|safetext}
                </td>
                <td headers="hdatasheet" class="z-left">
                      <a href="{$datasheet.datasheetFullPathURL}" title="{$datasheet.name|replace:"\"":""}"{if $datasheet.datasheetMeta.isImage} rel="imageviewer[datasheet]"{/if}>
                      {if $datasheet.datasheetMeta.isImage}
                          {thumb image=$datasheet.datasheetFullPath objectid="datasheet-`$datasheet.id`" manager=$datasheetThumbManagerDatasheet tag=true img_alt=$datasheet.name}
                      {else}
                          {gt text='Download'} ({$datasheet.datasheetMeta.size|datasheeteGetFileSize:$datasheet.datasheetFullPath:false:false})
                      {/if}
                      </a>
                </td>
                <td headers="hdescription" class="z-left">
                    {$datasheet.description}
                </td>
                <td id="itemactions{$datasheet.id}" headers="hitemactions" class="z-right z-nowrap z-w02">
                    {if count($datasheet._actions) gt 0}
                        {foreach item='option' from=$datasheet._actions}
                            <a href="{$option.url.type|datasheeteActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                        {/foreach}
                        {icon id="itemactions`$datasheet.id`trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                        <script type="text/javascript">
                        /* <![CDATA[ */
                            document.observe('dom:loaded', function() {
                                datashInitItemActions('datasheet', 'view', 'itemactions{{$datasheet.id}}');
                            });
                        /* ]]> */
                        </script>
                    {/if}
                </td>
            </tr>
        {foreachelse}
            <tr class="z-admintableempty">
              <td class="z-left" colspan="7">
            {gt text='No datasheets found.'}
              </td>
            </tr>
        {/foreach}
        
            </tbody>
        </table>
        
        {if !isset($showAllEntries) || $showAllEntries ne 1}
            {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
        {/if}
        <fieldset>
            <label for="datasheete_action">{gt text='With selected datasheets'}</label>
            <select id="datasheete_action" name="action">
                <option value="">{gt text='Choose action'}</option>
                <option value="delete">{gt text='Delete'}</option>
            </select>
            <input type="submit" value="{gt text='Submit'}" />
        </fieldset>
    </div>
</form>

</div>
{include file='admin/footer.tpl'}

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
    {{* init the "toggle all" functionality *}}
    if ($('toggle_datasheets') != undefined) {
        $('toggle_datasheets').observe('click', function (e) {
            Zikula.toggleInput('datasheets_view');
            e.stop()
        });
    }
    });
/* ]]> */
</script>
