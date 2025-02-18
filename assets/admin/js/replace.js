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
