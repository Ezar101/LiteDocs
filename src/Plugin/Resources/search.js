document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const resultsContainer = document.getElementById('search-results');

    if (!searchInput || !resultsContainer) return;

    function getIndex() {
        return window.PHP_DOCS_SEARCH_INDEX || [];
    }

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();

        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        const searchIndex = getIndex();

        const results = searchIndex.filter(item => {
            return (item.title && item.title.toLowerCase().includes(query)) || 
                   (item.text && item.text.toLowerCase().includes(query));
        });

        renderResults(results);
    });

    function renderResults(results) {
        const rootPath = document.documentElement.dataset.rootPath || './';

        if (results.length === 0) {
            resultsContainer.innerHTML = '<div class="no-results">No results</div>';
        } else {
            resultsContainer.innerHTML = results.map(item => `
                <a href="${rootPath}${item.url}" class="search-result-item">
                    <div class="result-title">${item.title}</div>
                    <div class="result-preview">${item.text.substring(0, 60)}...</div>
                </a>
            `).join('');
        }
        resultsContainer.style.display = 'block';
    }

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });
});
