const whereToReplace = document.getElementById('where_to_replace_rule');
const page = document.getElementById('page_replace_rule');
const post = document.getElementById('post_replace_rule');

const displayMap = {
    all: { page: false, post: false },
    page: { page: true, post: false },
    post: { page: false, post: true }
};

function updateDisplay(value) {
    const state = displayMap[value] || displayMap.all; // fallback به all
    page.style.display = state.page ? 'inline-block' : 'none';
    post.style.display = state.post ? 'inline-block' : 'none';
}

whereToReplace.addEventListener('change', function() {
    updateDisplay(this.value);
});

//initial load
updateDisplay('all');
