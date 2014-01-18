(function() {
    tinymce.create('tinymce.plugins.wpqca', {
        init : function(ed, url) {
            url = url.replace("../js","../images");
            ed.addButton('wpqca', {
                title : 'Block caching for this page',
                cmd : 'wpqca',
                image : url + "/icon.png"
            });
            ed.addCommand('wpqca', function() {
                ed.execCommand('mceInsertContent', 0, "[NoCache]");
            });
        }
    });
    tinymce.PluginManager.add( 'wpqca', tinymce.plugins.wpqca );
})();
