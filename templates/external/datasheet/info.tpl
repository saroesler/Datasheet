{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="datasheet{$datasheet.id}">
<dt>{$datasheet.name|notifyfilters:'datasheete.filter_hooks.datasheets.filter'|htmlentities}</dt>
{if $datasheet.description ne ''}<dd>{$datasheet.description}</dd>{/if}
</dl>
