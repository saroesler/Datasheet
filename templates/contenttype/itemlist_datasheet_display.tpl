{* Purpose of this template: Display datasheets within an external context *}
{foreach item='datasheet' from=$items}
    <h3>{$datasheet.name}</h3>
{/foreach}
