function showToast(message, type = 'success') {
    let root = document.getElementById('toast-root');

    if (!root) {
        root = document.createElement('div');
        root.id = 'toast-root';
        root.style.cssText = `
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            display: flex;
            flex-direction: column;
            gap: .5rem;
            z-index: 9999;
            width: min(360px, calc(100vw - 2rem));
        `;
        document.body.appendChild(root);
    }

    const styles = getComputedStyle(document.documentElement);
    const primary =
        styles.getPropertyValue('--color-primary')?.trim() || '#111827';
    const destructive =
        styles.getPropertyValue('--color-destructive')?.trim() || '#dc2626';

    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.cssText = `
        background: white;
        border: 1px solid rgba(229,231,235,1);
        border-left: 4px solid ${type === 'error' ? destructive : primary};
        padding: .5rem .75rem;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
        cursor: pointer;
        font-size: 14px;
        line-height: 1.25rem;
    `;

    root.appendChild(toast);
    const t = setTimeout(() => toast.remove(), 2500);
    toast.onclick = () => {
        clearTimeout(t);
        toast.remove();
    };
}

function initCommentAjax() {
    const form = document.getElementById('comment-form');
    if (!form || !window.fetch) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const textarea = document.getElementById('comment-content');
        const errorEl = document.getElementById('comment-error');
        const listEl = document.getElementById('comment-list');
        const countEl = document.getElementById('comment-count');
        const emptyEl = document.getElementById('no-comments');

        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
        }

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                const msg =
                    data?.errors?.content?.[0] ||
                    data?.message ||
                    'Lỗi gửi bình luận';

                if (errorEl) {
                    errorEl.textContent = msg;
                    errorEl.classList.remove('hidden');
                }
                showToast(msg, 'error');
                return;
            }

            if (emptyEl) emptyEl.remove();
            if (listEl) listEl.classList.remove('hidden');

            if (listEl && data?.comment) {
                // Avoid XSS: build nodes, don't inject raw HTML.
                const article = document.createElement('article');
                article.className =
                    'rounded border border-gray-200 border-l-2 border-l-primary/20 bg-white px-3 py-2';

                const strong = document.createElement('strong');
                strong.textContent = data.comment.author_name || '';

                const p = document.createElement('p');
                p.className = 'mt-1 text-sm text-body';
                p.textContent = data.comment.content || '';

                article.appendChild(strong);
                article.appendChild(p);
                listEl.appendChild(article);
            }

            if (textarea) textarea.value = '';
            if (countEl) {
                const n = Number(countEl.textContent || 0);
                countEl.textContent = String((Number.isFinite(n) ? n : 0) + 1);
            }

            showToast(data?.message || 'Đã gửi bình luận!');
        } catch {
            showToast('Không thể gửi bình luận', 'error');
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCommentAjax);
} else {
    initCommentAjax();
}
