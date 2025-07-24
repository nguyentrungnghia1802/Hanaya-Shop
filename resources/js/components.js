// Modal Alpine.js component - CSP compliant
window.ModalComponent = () => ({
    show: false,
    
    focusables() {
        // All focusable element types...
        let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
        return [...this.$el.querySelectorAll(selector)]
            // All non-disabled elements...
            .filter(el => !el.hasAttribute('disabled'));
    },
    
    firstFocusable() { 
        return this.focusables()[0]; 
    },
    
    lastFocusable() { 
        return this.focusables().slice(-1)[0]; 
    },
    
    nextFocusable() { 
        return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable(); 
    },
    
    prevFocusable() { 
        return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable(); 
    },
    
    nextFocusableIndex() { 
        return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1); 
    },
    
    prevFocusableIndex() { 
        return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1; 
    },
    
    init() {
        this.$watch('show', value => {
            if (value) {
                document.body.classList.add('overflow-y-hidden');
                // Focus first focusable element if modal is focusable
                if (this.$el.hasAttribute('data-focusable')) {
                    setTimeout(() => {
                        const first = this.firstFocusable();
                        if (first) first.focus();
                    }, 100);
                }
            } else {
                document.body.classList.remove('overflow-y-hidden');
            }
        });
    },
    
    handleKeydownTab(event) {
        if (event.shiftKey) {
            return;
        }
        event.preventDefault();
        const next = this.nextFocusable();
        if (next) next.focus();
    },
    
    handleKeydownShiftTab(event) {
        event.preventDefault();
        const prev = this.prevFocusable();
        if (prev) prev.focus();
    }
});

// Navigation Alpine.js component - CSP compliant
window.NavigationComponent = () => ({
    open: false,
    loading: false,
    
    // Helper methods for class conditionals - CSP compliant
    getHamburgerIconClass() {
        return this.open ? 'hidden' : 'inline-flex';
    },
    
    getCloseIconClass() {
        return this.open ? 'inline-flex' : 'hidden';
    },
    
    getResponsiveMenuClass() {
        return this.open ? 'block' : 'hidden';
    }
});

// Dropdown Alpine.js component - CSP compliant
window.DropdownComponent = () => ({
    open: false
});
