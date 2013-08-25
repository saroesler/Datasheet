{* Purpose of this template: Display datasheets within an external context *}
<dl>
    {foreach item='datasheet' from=$items}
        <dt>{$datasheet.name}</dt>
        {if $datasheet.description}
            <dd>{$datasheet.description|truncate:200:"..."}</dd>
        {/if}
        <dd><a href="{modurl modname='Datasheete' type='user' func='display' ot=$objectType id=$item.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>
