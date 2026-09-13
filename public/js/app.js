document.documentElement.classList.add('js-ready');

document.addEventListener('click', (event) => {
    const card = event.target.closest('[data-product-url]');

    if (card && event.button === 0 && !event.defaultPrevented) {
        event.preventDefault();
        window.location.assign(card.dataset.productUrl);
    }
});
