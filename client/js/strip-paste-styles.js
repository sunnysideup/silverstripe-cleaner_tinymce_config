/* Removes inline styles and style/meta/font cruft from pasted content. Classes are left intact. */
(function () {
    tinymce.PluginManager.add('strippastestyles', function (editor) {
        editor.on('PastePostProcess', function (e) {
            var node = e.node;
            node.querySelectorAll('[style]').forEach(function (el) {
                el.removeAttribute('style');
            });
            node.querySelectorAll('style, meta, link').forEach(function (el) {
                el.remove();
            });
            // Unwrap legacy <font> tags
            node.querySelectorAll('font').forEach(function (el) {
                while (el.firstChild) {
                    el.parentNode.insertBefore(el.firstChild, el);
                }
                el.remove();
            });
        });
    });
})();
