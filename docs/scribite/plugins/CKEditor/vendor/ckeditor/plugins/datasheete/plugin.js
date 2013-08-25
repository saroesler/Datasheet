CKEDITOR.plugins.add('Datasheete', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertDatasheete', {
            exec: function (editor) {
                var url = Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=Datasheete&type=external&func=finder&editor=ckeditor';
                // call method in Datasheete_Finder.js and also give current editor
                DatasheeteFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('datasheete', {
            label: 'Insert Datasheete object',
            command: 'insertDatasheete',
         // icon: this.path + 'images/ed_datasheete.png'
            icon: '/images/icons/extrasmall/favorites.png'
        });
    }
});
