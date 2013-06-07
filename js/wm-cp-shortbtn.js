(function() {
   tinymce.create('tinymce.plugins.wmcp', {
      init : function(ed, url) {
         ed.addCommand('wmchildpost', function(){
            var wmpopup = jQuery('#wm-container');
            wmpopup.show(100);
         });
         ed.addButton('wmcp', {
            title : 'WM Child Posts',
            cmd : 'wmchildpost',
            image : url+'/icon.png',
         });
      },
      createControl : function(n, cm) {
         return null;
      }
   });
   tinymce.PluginManager.add('wmcp', tinymce.plugins.wmcp);
})();