/**
 * Text Formatter - Simple formatting toolbar for textareas
 * 
 * Provides bold, italic, and underline formatting functionality
 * for textarea elements with the class 'formatted-textarea'.
 */

document.addEventListener('DOMContentLoaded', () => {
    // Find all formatted textareas
    const textareas = document.querySelectorAll('.formatted-textarea');
    
    textareas.forEach(textarea => {
        // Create toolbar container
        const toolbar = document.createElement('div');
        toolbar.className = 'format-toolbar flex items-center space-x-2 mb-2 p-2 bg-gray-100 rounded-md';
        
        // Create formatter buttons
        const buttons = [
            {
                name: 'bold',
                icon: 'B',
                title: 'Bold',
                className: 'font-bold'
            },
            {
                name: 'italic',
                icon: 'I',
                title: 'Italic',
                className: 'italic'
            },
            {
                name: 'underline',
                icon: 'U',
                title: 'Underline',
                className: 'underline'
            }
        ];
        
        // Create and add buttons to toolbar
        buttons.forEach(btn => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `px-3 py-1 bg-white hover:bg-gray-200 rounded border border-gray-300 ${btn.className}`;
            button.innerHTML = btn.icon;
            button.title = btn.title;
            button.addEventListener('click', () => formatText(textarea, btn.name));
            
            toolbar.appendChild(button);
        });
        
        // Add a small preview toggle button
        const previewContainer = document.createElement('div');
        previewContainer.className = 'preview-container mt-2 p-3 bg-white border border-gray-300 rounded-md hidden';
        previewContainer.style.maxHeight = '300px';
        previewContainer.style.overflowY = 'auto';
        
        const previewButton = document.createElement('button');
        previewButton.type = 'button';
        previewButton.className = 'ml-auto px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm';
        previewButton.innerHTML = 'Preview';
        previewButton.title = 'Preview formatted text';
        
        // Toggle preview functionality
        let previewVisible = false;
        previewButton.addEventListener('click', () => {
            if (previewVisible) {
                previewContainer.classList.add('hidden');
                previewButton.innerHTML = 'Preview';
                previewButton.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                previewButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
            } else {
                previewContainer.innerHTML = textarea.value;
                previewContainer.classList.remove('hidden');
                previewButton.innerHTML = 'Hide Preview';
                previewButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                previewButton.classList.add('bg-gray-500', 'hover:bg-gray-600');
            }
            previewVisible = !previewVisible;
        });
        
        toolbar.appendChild(previewButton);
        
        // Help text 
        const helpText = document.createElement('div');
        helpText.className = 'text-xs text-gray-500 ml-2';
        helpText.innerHTML = 'Select text, then click a format button';
        toolbar.appendChild(helpText);
        
        // Insert toolbar before textarea and preview after textarea
        textarea.parentNode.insertBefore(toolbar, textarea);
        textarea.parentNode.insertBefore(previewContainer, textarea.nextSibling);
        
        // Update preview when textarea changes
        textarea.addEventListener('input', () => {
            if (previewVisible) {
                previewContainer.innerHTML = textarea.value;
            }
        });
    });
});

/**
 * Format selected text in a textarea
 * 
 * @param {HTMLTextAreaElement} textarea - The textarea element
 * @param {string} format - The format to apply: 'bold', 'italic', or 'underline'
 */
function formatText(textarea, format) {
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    if (selectedText === '') {
        return; // No text selected
    }
    
    let formattedText = '';
    
    switch (format) {
        case 'bold':
            formattedText = `<strong>${selectedText}</strong>`;
            break;
        case 'italic':
            formattedText = `<em>${selectedText}</em>`;
            break;
        case 'underline':
            formattedText = `<u>${selectedText}</u>`;
            break;
    }
    
    // Replace the selected text with the formatted text
    textarea.value = 
        textarea.value.substring(0, start) + 
        formattedText + 
        textarea.value.substring(end);
    
    // Restore focus and set selection after the formatted text
    textarea.focus();
    const newPosition = start + formattedText.length;
    textarea.setSelectionRange(newPosition, newPosition);
    
    // Trigger input event to update preview if visible
    const inputEvent = new Event('input', {
        bubbles: true,
        cancelable: true,
    });
    textarea.dispatchEvent(inputEvent);
} 