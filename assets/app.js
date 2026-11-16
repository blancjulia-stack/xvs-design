import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

 (function () {
            var scene = document.querySelector('.spb-scene');
            if (!scene) return;
            function update() {
                var max = document.documentElement.scrollHeight - window.innerHeight;
                scene.style.setProperty('--spb-p', max > 0 ? Math.min(1, window.scrollY / max) : 0);
            }
            window.addEventListener('scroll', update, { passive: true });
            window.addEventListener('resize', update);
            update();
        })();