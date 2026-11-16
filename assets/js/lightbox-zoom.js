// Clic sur une image de la galerie : elle s'ouvre dans une NOUVELLE FENÊTRE du navigateur,
// avec à côté une zone qui affiche un zoom quand on survole l'image.
// L'écoute du clic est faite sur tout le document : ça marche aussi sur les cartes
// ajoutées en arrière-plan par gallery-ajax.js.
(function () {
    'use strict';

    const ZOOM = 2.5;                 // facteur d'agrandissement de la zone de droite
    const WINDOW_NAME = 'xvs-image-viewer'; // même nom = on réutilise la même fenêtre à chaque clic

    // Curseur « loupe » sur les images cliquables
    const style = document.createElement('style');
    style.textContent = 'img.portfolio-img { cursor: zoom-in; }';
    document.head.appendChild(style);

    // ---- Code exécuté DANS la nouvelle fenêtre (envoyé sous forme de texte) ----
    function viewerMain(data) {
        var img = document.getElementById('img');
        var stage = document.getElementById('stage');
        var lens = document.getElementById('lens');
        var pane = document.getElementById('pane');

        document.title = data.alt || 'Image';
        img.src = data.src;
        img.alt = data.alt || '';
        document.getElementById('cap').textContent = data.alt || '';
        pane.style.backgroundImage = 'url(' + JSON.stringify(data.src) + ')';

        function idle() {
            stage.classList.remove('zooming');
            pane.classList.add('idle');
        }

        stage.addEventListener('pointermove', function (e) {
            if (e.pointerType === 'touch') return;
            var r = img.getBoundingClientRect();
            var pr = pane.getBoundingClientRect();
            if (!r.width || !pr.width) return;

            var x = Math.min(Math.max(e.clientX - r.left, 0), r.width);
            var y = Math.min(Math.max(e.clientY - r.top, 0), r.height);

            // zone de l'image visible dans le panneau de zoom (en pixels affichés)
            var lw = pr.width / data.zoom;
            var lh = pr.height / data.zoom;
            var lx = Math.min(Math.max(x - lw / 2, 0), Math.max(0, r.width - lw));
            var ly = Math.min(Math.max(y - lh / 2, 0), Math.max(0, r.height - lh));

            lens.style.width = Math.min(lw, r.width) + 'px';
            lens.style.height = Math.min(lh, r.height) + 'px';
            lens.style.left = lx + 'px';
            lens.style.top = ly + 'px';

            pane.style.backgroundSize = (r.width * data.zoom) + 'px ' + (r.height * data.zoom) + 'px';
            pane.style.backgroundPosition = (-lx * data.zoom) + 'px ' + (-ly * data.zoom) + 'px';

            stage.classList.add('zooming');
            pane.classList.remove('idle');
        });
        stage.addEventListener('pointerleave', idle);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') window.close();
        });
        idle();
    }

    var VIEWER_CSS =
        'html,body{margin:0;height:100%;background:#0b0b10;color:#f3eee6;font-family:system-ui,sans-serif}' +
        '.wrap{min-height:100%;box-sizing:border-box;padding:1.5rem;display:flex;align-items:center;justify-content:center;gap:1.5rem}' +
        'figure{margin:0}' +
        '#stage{position:relative;display:inline-block;line-height:0;cursor:crosshair}' +
        '#img{display:block;max-width:calc(100vw - 31rem);max-height:calc(100vh - 6rem);border-radius:6px;box-shadow:0 10px 40px rgba(0,0,0,.6)}' +
        '#lens{position:absolute;display:none;pointer-events:none;border:2px solid #b3122b;background:rgba(179,18,43,.12);box-sizing:border-box}' +
        '#stage.zooming #lens{display:block}' +
        '#cap{margin-top:.75rem;color:#cfc8bd;font-size:.95rem;line-height:1.4;text-align:center}' +
        '#pane{flex:0 0 26rem;width:26rem;height:min(26rem,calc(100vh - 3rem));display:flex;align-items:center;justify-content:center;' +
            'border:1px solid rgba(255,255,255,.18);border-radius:6px;background:#101015 no-repeat}' +
        '#pane.idle{background-image:none!important}' +
        '#pane:not(.idle) .hint{display:none}' +
        '.hint{padding:1.5rem;color:#8d877e;font-size:.95rem;text-align:center}' +
        '@media (max-width:900px){#pane,#lens{display:none!important}#img{max-width:94vw}#stage{cursor:default}}';

    function viewerHtml(data) {
        // '<' échappé : aucune donnée ne peut fermer la balise <script>
        var json = JSON.stringify(data).replace(/</g, '\\u003c');
        return '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8">' +
            '<meta name="viewport" content="width=device-width,initial-scale=1">' +
            '<title>Image</title><style>' + VIEWER_CSS + '</style></head><body>' +
            '<div class="wrap">' +
                '<figure><div id="stage"><img id="img" alt=""><div id="lens"></div></div><figcaption id="cap"></figcaption></figure>' +
                '<div id="pane" class="idle"><span class="hint">Passe la souris sur l\u2019image pour zoomer</span></div>' +
            '</div>' +
            '<script>(' + viewerMain.toString() + ')(' + json + ');<\/script>' +
            '</body></html>';
    }

    function openViewer(img) {
        var data = { src: img.currentSrc || img.src, alt: img.alt || '', zoom: ZOOM };

        var w = Math.min(1280, screen.availWidth - 60);
        var h = Math.min(860, screen.availHeight - 80);
        var left = Math.max(0, Math.round((screen.availWidth - w) / 2));
        var top = Math.max(0, Math.round((screen.availHeight - h) / 2));

        var win = window.open('', WINDOW_NAME,
            'popup=yes,width=' + w + ',height=' + h + ',left=' + left + ',top=' + top);

        if (!win) {            // fenêtre bloquée par le navigateur : on ouvre l'image seule
            console.warn('[zoom] Nouvelle fenêtre bloquée par le navigateur (pop-ups).');
            window.location.href = data.src;
            return;
        }

        win.document.open();
        win.document.write(viewerHtml(data));
        win.document.close();
        win.focus();
    }

    // Clic n'importe où sur une carte (pas seulement sur l'image) : un calque ou un effet
    // posé par-dessus l'image n'empêche donc plus l'ouverture. Phase de capture (true) :
    // un autre script qui bloquerait la propagation du clic ne peut pas nous couper.
    document.addEventListener('click', function (e) {
        if (!e.target.closest || e.target.closest('a, button')) return;
        var card = e.target.closest('.portfolio-card-bodys');
        var img = card ? card.querySelector('img.portfolio-img') : e.target.closest('img.portfolio-img');
        if (img) openViewer(img);
    }, true);
})();
