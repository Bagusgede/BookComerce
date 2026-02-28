import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Import CKEditor initializer (requires npm package @ckeditor/ckeditor5-build-classic)
try {
	import('./ckeditor')
		.catch(() => {
			// ignore when module not available (developer can install via npm)
		});
} catch (e) {
	// ignore when dynamic import not supported
}
