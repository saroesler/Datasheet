'use strict';

var currentDatasheeteEditor = null;
var currentDatasheeteInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPopupAttributes() {
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;
    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a Xinha button.
 */
function DatasheeteFinderXinha(editor, datashURL) {
    var popupAttributes;

    // Save editor for access in selector window
    currentDatasheeteEditor = editor;

    popupAttributes = getPopupAttributes();
    window.open(datashURL, '', popupAttributes);
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function DatasheeteFinderCKEditor(editor, datashURL) {
    // Save editor for access in selector window
    currentDatasheeteEditor = editor;

    editor.popup(
        Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=Datasheete&type=external&func=finder&editor=ckeditor',
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}



var datasheete = {};

datasheete.finder = {};

datasheete.finder.onLoad = function (baseId, selectedId) {
    $('Datasheete_sort').observe('change', datasheete.finder.onParamChanged);
    $('Datasheete_sortdir').observe('change', datasheete.finder.onParamChanged);
    $('Datasheete_pagesize').observe('change', datasheete.finder.onParamChanged);
    $('Datasheete_gosearch').observe('click', datasheete.finder.onParamChanged)
                           .observe('keypress', datasheete.finder.onParamChanged);
    $('Datasheete_submit').addClassName('z-hide');
    $('Datasheete_cancel').observe('click', datasheete.finder.handleCancel);
};

datasheete.finder.onParamChanged = function () {
    $('selectorForm').submit();
};

datasheete.finder.handleCancel = function () {
    var editor, w;

    editor = $F('editorName');
    if (editor === 'xinha') {
        w = parent.window;
        window.close();
        w.focus();
    } else if (editor === 'tinymce') {
        datashClosePopup();
    } else if (editor === 'ckeditor') {
        datashClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function getPasteSnippet(mode, itemId) {
    var itemUrl, itemTitle, itemDescription, pasteMode;

    itemUrl = $F('url' + itemId);
    itemTitle = $F('title' + itemId);
    itemDescription = $F('desc' + itemId);
    pasteMode = $F('Datasheete_pasteas');

    if (pasteMode === '2' || pasteMode !== '1') {
        return itemId;
    }

    // return link to item
    if (mode === 'url') {
        // plugin mode
        return itemUrl;
    } else {
        // editor mode
        return '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
}


// User clicks on "select item" button
datasheete.finder.selectItem = function (itemId) {
    var editor, html;

    editor = $F('editorName');
    if (editor === 'xinha') {
        if (window.opener.currentDatasheeteEditor !== null) {
            html = getPasteSnippet('html', itemId);

            window.opener.currentDatasheeteEditor.focusEditor();
            window.opener.currentDatasheeteEditor.insertHTML(html);
        } else {
            html = getPasteSnippet('url', itemId);
            var currentInput = window.opener.currentDatasheeteInput;

            if (currentInput.tagName === 'INPUT') {
                // Simply overwrite value of input elements
                currentInput.value = html;
            } else if (currentInput.tagName === 'TEXTAREA') {
                // Try to paste into textarea - technique depends on environment
                if (typeof document.selection !== 'undefined') {
                    // IE: Move focus to textarea (which fortunately keeps its current selection) and overwrite selection
                    currentInput.focus();
                    window.opener.document.selection.createRange().text = html;
                } else if (typeof currentInput.selectionStart !== 'undefined') {
                    // Firefox: Get start and end points of selection and create new value based on old value
                    var startPos = currentInput.selectionStart;
                    var endPos = currentInput.selectionEnd;
                    currentInput.value = currentInput.value.substring(0, startPos)
                                        + html
                                        + currentInput.value.substring(endPos, currentInput.value.length);
                } else {
                    // Others: just append to the current value
                    currentInput.value += html;
                }
            }
        }
    } else if (editor === 'tinymce') {
        html = getPasteSnippet('html', itemId);
        window.opener.tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if (editor === 'ckeditor') {
        /** to be done*/
    } else {
        alert('Insert into Editor: ' + editor);
    }
    datashClosePopup();
};


function datashClosePopup() {
    window.opener.focus();
    window.close();
}




//=============================================================================
// Datasheete item selector for Forms
//=============================================================================

datasheete.itemSelector = {};
datasheete.itemSelector.items = {};
datasheete.itemSelector.baseId = 0;
datasheete.itemSelector.selectedId = 0;

datasheete.itemSelector.onLoad = function (baseId, selectedId) {
    datasheete.itemSelector.baseId = baseId;
    datasheete.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    $(baseId + '_objecttype').observe('change', datasheete.itemSelector.onParamChanged);

    if ($(baseId + '_catid') !== undefined) {
        $(baseId + '_catid').observe('change', datasheete.itemSelector.onParamChanged);
    }
    $(baseId + '_id').observe('change', datasheete.itemSelector.onItemChanged);
    $(baseId + '_sort').observe('change', datasheete.itemSelector.onParamChanged);
    $(baseId + '_sortdir').observe('change', datasheete.itemSelector.onParamChanged);
    $('Datasheete_gosearch').observe('click', datasheete.itemSelector.onParamChanged)
                           .observe('keypress', datasheete.itemSelector.onParamChanged);

    datasheete.itemSelector.getItemList();
};

datasheete.itemSelector.onParamChanged = function () {
    $('ajax_indicator').removeClassName('z-hide');

    datasheete.itemSelector.getItemList();
};

datasheete.itemSelector.getItemList = function () {
    var baseId, pars, request;

    baseId = datasheete.itemSelector.baseId;
    pars = 'objectType=' + baseId + '&';
    if ($(baseId + '_catid') !== undefined) {
        pars += 'catid=' + $F(baseId + '_catid') + '&';
    }
    pars += 'sort=' + $F(baseId + '_sort') + '&' +
            'sortdir=' + $F(baseId + '_sortdir') + '&' +
            'searchterm=' + $F(baseId + '_searchterm');

    request = new Zikula.Ajax.Request('ajax.php?module=Datasheete&func=getItemListFinder', {
        method: 'post',
        parameters: pars,
        onFailure: function(req) {
            Zikula.showajaxerror(req.getMessage());
        },
        onSuccess: function(req) {
            var baseId;
            baseId = datasheete.itemSelector.baseId;
            datasheete.itemSelector.items[baseId] = req.getData();
            $('ajax_indicator').addClassName('z-hide');
            datasheete.itemSelector.updateItemDropdownEntries();
            datasheete.itemSelector.updatePreview();
        }
    });
};

datasheete.itemSelector.updateItemDropdownEntries = function () {
    var baseId, itemSelector, items, i, item;

    baseId = datasheete.itemSelector.baseId;
    itemSelector = $(baseId + '_id');
    itemSelector.length = 0;

    items = datasheete.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.options[i] = new Option(item.title, item.id, false);
    }

    if (datasheete.itemSelector.selectedId > 0) {
        $(baseId + '_id').value = datasheete.itemSelector.selectedId;
    }
};

datasheete.itemSelector.updatePreview = function () {
    var baseId, items, selectedElement, i;

    baseId = datasheete.itemSelector.baseId;
    items = datasheete.itemSelector.items[baseId];

    $(baseId + '_previewcontainer').addClassName('z-hide');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (datasheete.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id === datasheete.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (selectedElement !== null) {
        $(baseId + '_previewcontainer').update(window.atob(selectedElement.previewInfo))
                                       .removeClassName('z-hide');
    }
};

datasheete.itemSelector.onItemChanged = function () {
    var baseId, itemSelector, preview;

    baseId = datasheete.itemSelector.baseId;
    itemSelector = $(baseId + '_id');
    preview = window.atob(datasheete.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    $(baseId + '_previewcontainer').update(preview);
    datasheete.itemSelector.selectedId = $F(baseId + '_id');
};
