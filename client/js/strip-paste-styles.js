/*
 * Cleans pasted content: removes inline styles, most class attributes,
 * <style>/<link> tags and <font> tags.
 *
 * Hooks several points of TinyMCE's paste pipeline (PastePostProcess,
 * BeforeSetContent with paste flag, and a parser filter) because which one
 * is reached depends on the browser/clipboard path. Cleaning is idempotent.
 * Typing and loading saved content are unaffected.
 *
 * Classes in ALLOWED_CLASSES survive, so editor-applied styles are not lost.
 */
(function () {
    var ALLOWED_CLASSES = ['btn-link', 'free-sized-image', 'accordion-header', 'accordion-content'];
    function cleanElement(root) {
        root.querySelectorAll('style, link, meta').forEach(function (el) { el.remove(); });
        root.querySelectorAll('font').forEach(function (el) {
            while (el.firstChild) { el.parentNode.insertBefore(el.firstChild, el); }
            el.remove();
        });
        root.querySelectorAll('[style]').forEach(function (el) { el.removeAttribute('style'); });
        root.querySelectorAll('[class]').forEach(function (el) {
            var keep = (el.getAttribute('class') || '').split(/\s+/).filter(function (c) {
                return ALLOWED_CLASSES.indexOf(c) !== -1;
            });
            if (keep.length) { el.setAttribute('class', keep.join(' ')); } else { el.removeAttribute('class'); }
        });
    }

    function cleanHtml(html) {
        var doc = new DOMParser().parseFromString('<body>' + html + '</body>', 'text/html');
        cleanElement(doc.body);
        return doc.body.innerHTML;
    }

    tinymce.PluginManager.add('strippastestyles', function (editor) {

        editor.on('PastePreProcess', function (e) {
            e.content = cleanHtml(e.content);
        });

        editor.on('PastePostProcess', function (e) {
            cleanElement(e.node);
        });

        editor.on('BeforeSetContent', function (e) {
            if (e.paste || (e.data && e.data.paste)) {
                if (typeof e.content === 'string') { e.content = cleanHtml(e.content); }
            }
        });
    });
})();
