/*
 * Restores the editor height after leaving full-screen.
 *
 * silverstripe/htmleditor-tinymce sets a fixed height on .tox-sidebar-wrap
 * (rows x fixed_row_height) when the editor is initialised, and clears it again
 * on every ResizeEditor event (so that a manual drag-resize wins).
 * The fullscreen plugin fires ResizeEditor when entering and leaving full-screen,
 * so after leaving full-screen the editable area is left without any height and
 * only the toolbar remains visible.
 *
 * We remember the height just before it is cleared (on entering full-screen) and
 * put it back once the fullscreen plugin and the core handler have finished.
 */
(function () {
    tinymce.PluginManager.add('fullscreenrestoreheight', function (editor) {
        var saved = null;

        function getParts() {
            var iframe = editor.iframeElement;
            if (!iframe) { return null; }
            return { iframe: iframe, wrap: iframe.closest('.tox-sidebar-wrap') };
        }

        editor.on('FullscreenStateChanged', function (e) {
            var parts = getParts();
            if (!parts || !parts.wrap) { return; }
            if (e.state) {
                // fires before ResizeEditor clears the height
                saved = { wrapHeight: parts.wrap.style.height, iframeHeight: parts.iframe.getAttribute('height') };
                return;
            }
            if (!saved) { return; }
            var restore = saved;
            saved = null;
            // ResizeEditor is dispatched synchronously after this event, so wait for it.
            setTimeout(function () {
                var p = getParts();
                if (!p || !p.wrap) { return; }
                p.wrap.style.height = restore.wrapHeight;
                if (restore.iframeHeight === null) {
                    p.iframe.removeAttribute('height');
                } else {
                    p.iframe.setAttribute('height', restore.iframeHeight);
                }
            }, 0);
        });
    });
})();
