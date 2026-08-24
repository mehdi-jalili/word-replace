<<<<<<< HEAD
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
=======
const whereToReplace = document.querySelector('#where_to_replace_rule');
const page = document.querySelector('#page_replace_rule');
const post = document.querySelector('#post_replace_rule');

whereToReplace.addEventListener('change', function() {
    if (this.value === 'all') {
        page.style.display = "none";
        post.style.display = "none";
    }
    else if(this.value === 'page'){
        page.style.display = "inline-block";
        post.style.display = "none";
    }
    else {
    page.style.display = "none";
    post.style.display = "inline-block";
    }
});

//initial load
page.style.display = "none";
post.style.display = "none";
>>>>>>> a17dcb73cc217c0ca88508842ad816bfa013fb83
