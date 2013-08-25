// Datasheete plugin for Xinha
// developed by Sascha Rösler
//
// requires Datasheete module (http://github.com/sarom5)
//
// Distributed under the same terms as xinha itself.
// This notice MUST stay intact for use (see license.txt).

'use strict';

function Datasheete(editor) {
    var cfg, self;

    this.editor = editor;
    cfg = editor.config;
    self = this;

    cfg.registerButton({
        id       : 'Datasheete',
        tooltip  : 'Insert Datasheete object',
     // image    : _editor_url + 'plugins/Datasheete/img/ed_Datasheete.gif',
        image    : '/images/icons/extrasmall/favorites.png',
        textMode : false,
        action   : function (editor) {
            var url = Zikula.Config.baseURL + 'index.php'/*Zikula.Config.entrypoint*/ + '?module=Datasheete&type=external&func=finder&editor=xinha';
            DatasheeteFinderXinha(editor, url);
        }
    });
    cfg.addToolbarElement('Datasheete', 'insertimage', 1);
}

Datasheete._pluginInfo = {
    name          : 'Datasheete for xinha',
    version       : '1.0.0',
    developer     : 'Sascha Rösler',
    developer_url : 'http://github.com/sarom5',
    sponsor       : 'ModuleStudio 0.6.0',
    sponsor_url   : 'http://modulestudio.de',
    license       : 'htmlArea'
};
