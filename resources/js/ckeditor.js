import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.ckeditorInstances = {};

function initializeCKEditor() {
    const textareas = Array.from(document.querySelectorAll('textarea[name="content"], textarea[name="description"], .js-ckeditor'));

    if (!textareas.length) {
        return;
    }

    textareas.forEach((textarea, index) => {
        if (textarea.dataset.ckeditorInitialized) {
            return;
        }

        textarea.dataset.ckeditorInitialized = true;

        // Toolbar yang simple dan berhasil
        ClassicEditor.create(textarea, {
            toolbar: [
                'bold',
                'italic',
                'underline',
                '|',
                'link',
                '|',
                'bulletedList',
                'numberedList',
                '|',
                'blockQuote',
                '|',
                'undo',
                'redo'
            ]
        })
            .then(editor => {
                console.log('✅ CKEditor initialized!');

                window.ckeditorInstance = editor;

                const editorKey = textarea.name || textarea.id || `editor_${index}`;
                window.ckeditorInstances[editorKey] = editor;

                if (index === 0) {
                    addHeadingButtons(editor);
                }

                const form = textarea.closest('form');
                if (form) {
                    form.addEventListener('submit', function () {
                        textarea.value = editor.getData();
                    });
                }
            })
            .catch(error => {
                console.error('CKEditor error:', error);
            });
    });
}

function addHeadingButtons(editor) {
    // Wait for toolbar to be rendered
    setTimeout(() => {
        const toolbarElement = document.querySelector('.ck-toolbar');
        
        if (!toolbarElement) {
            console.log('Toolbar not ready yet');
            return;
        }

        // Create heading buttons
        const headingGroup = document.createElement('div');
        headingGroup.style.cssText = `
            display: flex;
            gap: 8px;
            padding-right: 10px;
            border-right: 1px solid #d1d5db;
            margin-right: 10px;
        `;
        
        const headings = [
            { label: 'H1', level: 1, title: 'Heading 1' },
            { label: 'H2', level: 2, title: 'Heading 2' },
            { label: 'H3', level: 3, title: 'Heading 3' }
        ];
        
        headings.forEach(heading => {
            const btn = document.createElement('button');
            btn.textContent = heading.label;
            btn.title = heading.title;
            btn.type = 'button';
            btn.className = 'ck-button ck-button_with-text';
            btn.style.cssText = `
                padding: 6px 10px;
                background: #f3f4f6;
                border: 1px solid #d1d5db;
                border-radius: 2px;
                cursor: pointer;
                font-weight: 600;
                font-size: 12px;
                transition: all 0.2s;
            `;
            
            btn.onmouseover = () => {
                btn.style.background = '#e5e7eb';
            };
            btn.onmouseout = () => {
                btn.style.background = '#f3f4f6';
            };
            
            btn.onclick = (e) => {
                e.preventDefault();
                insertHeading(editor, heading.level);
            };
            
            headingGroup.appendChild(btn);
        });
        
        // Insert at the beginning of toolbar
        toolbarElement.insertBefore(headingGroup, toolbarElement.firstChild);
    }, 200);
}

function insertHeading(editor, level) {
    const model = editor.model;
    const doc = model.document;
    const selection = doc.selection;
    
    model.change(writer => {
        // Get all selected blocks
        const blocks = Array.from(selection.getSelectedBlocks());
        
        // If nothing is selected, get the current block
        if (blocks.length === 0) {
            const pos = selection.getFirstPosition();
            if (pos) {
                let element = pos.parent;
                // Find the nearest block element
                while (element && !model.schema.isBlock(element)) {
                    element = element.parent;
                }
                if (element) {
                    blocks.push(element);
                }
            }
        }
        
        // Apply heading to all selected blocks
        blocks.forEach(element => {
            // Only convert block-level elements
            if (model.schema.isBlock(element)) {
                try {
                    // Use CKEditor's heading command if available
                    const headingName = `heading${level}`;
                    writer.rename(element, headingName);
                } catch (e) {
                    // Fallback if rename doesn't work
                    console.warn('Could not apply heading:', e);
                }
            }
        });
    });
    
    editor.editing.view.focus();
}

// Initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(initializeCKEditor, 100);
    });
} else {
    setTimeout(initializeCKEditor, 100);
}

window.addEventListener('load', () => {
    setTimeout(initializeCKEditor, 200);
});