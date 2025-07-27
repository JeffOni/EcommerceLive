// Force reload Livewire components when page loads
document.addEventListener('DOMContentLoaded', function () {
    // Force refresh all Livewire components
    if (window.Livewire) {
        window.Livewire.restart();
        console.log('Livewire components restarted to show latest offers');
    }

    // Force reload of specific product components
    const productComponents = document.querySelectorAll('[wire\\:id]');
    productComponents.forEach(component => {
        const wireId = component.getAttribute('wire:id');
        if (wireId && window.Livewire) {
            console.log('Refreshing component:', wireId);
            window.Livewire.rescan();
        }
    });

    console.log('Offer display refresh completed');
});
