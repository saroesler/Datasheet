{* Purpose of this template: Display one certain datasheet within an external context *}
<div id="datasheet{$datasheet.id}" class="datashexternaldatasheet">
{if $displayMode eq 'link'}
    <p>
    {$datasheet.name|notifyfilters:'datasheete.filter_hooks.datasheets.filter'}
    </p>
{/if}
{checkpermissionblock component='Datasheete::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="datashexternaltitle">
            <strong>{$datasheet.name|notifyfilters:'datasheete.filter_hooks.datasheets.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="datashexternalsnippet">
        &nbsp;
    </div>

    {* you can distinguish the context like this: *}
    {*if $source eq 'contentType'}
        ...
    {elseif $source eq 'scribite'}
        ...
    {/if*}

    {* you can enable more details about the item: *}
    {*
        <p class="datashexternaldesc">
            {if $datasheet.description ne ''}{$datasheet.description}<br />{/if}
        </p>
    *}
{/if}
</div>
