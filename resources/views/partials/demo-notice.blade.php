<div class="demo-notice" role="note">
    <span class="material-symbols-outlined" aria-hidden="true">info</span>
    <span><strong>Portfolio Live Demo:</strong> This e-commerce store is for demonstration purposes only. No real transaction shall be made. Sample work by Ronalyn Tolosa. Copyright {{ date('Y') }}.</span>
</div>
<style>
    .demo-notice {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        display: flex;
        width: 100%;
        min-height: 36px;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 20px;
        background: #201a17;
        color: #fffdfc;
        font-family: Inter, sans-serif;
        font-size: 11px;
        line-height: 1.35;
        letter-spacing: .02em;
        text-align: center;
    }
    .demo-notice .material-symbols-outlined { color: #d58a73; font-size: 16px; }
    .demo-notice strong { color: #e7a18c; }
    .demo-notice + nav,
    .demo-notice + header { top: 36px !important; }
    body:has(.demo-notice) nav,
    body:has(.demo-notice) header { top: 36px !important; }
    body:has(.demo-notice) aside { top: 36px !important; height: calc(100% - 36px) !important; }
    @media (max-width: 640px) {
        .demo-notice { justify-content: flex-start; padding: 7px 12px; font-size: 10px; }
        .demo-notice + nav,
        .demo-notice + header { top: 48px !important; }
        body:has(.demo-notice) nav,
        body:has(.demo-notice) header { top: 48px !important; }
        body:has(.demo-notice) aside { top: 48px !important; height: calc(100% - 48px) !important; }
    }
</style>
