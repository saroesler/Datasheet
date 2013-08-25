{* Purpose of this template: Display datasheets in text mailings *}
{foreach item='datasheet' from=$items}
{$datasheet.name}
{modurl modname='Datasheete' type='user' func='display' ot=$objectType id=$datasheet.id fqurl=true}
-----
{foreachelse}
{gt text='No datasheets found.'}
{/foreach}
