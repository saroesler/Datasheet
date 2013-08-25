{* Purpose of this template: Display datasheets in html mailings *}
{*
<ul>
{foreach item='datasheet' from=$items}
    <li>
        <a href="{homepage}
        ">{$datasheet.name}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No datasheets found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_datasheet_display_description.tpl'}
