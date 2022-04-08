let sortDownLength = document.querySelector('.sort-length-down');
let sortUpLength = document.querySelector('.sort-length-up');

console.log(sortUpLength)
sortUpLength.addEventListener('click', () => {
    document.cookie = 'sort=up; path=/;';
    location.href = location.href

})

sortDownLength.addEventListener('click', () => {

    document.cookie = 'sort=down; path=/;';
    location.href = location.href
})


function loadMore() {
    let morePosts = document.querySelector('.load_more');
    if (!morePosts) return false;

    morePosts.addEventListener('click', () => {

        let formData = new FormData();
        formData.append('action', 'load_more_posts');
        formData.append('query', myajax.posts);
        formData.append('page', myajax.current_page);

        fetch(myajax.url, {
            method: 'POST',
            body: formData,
            'Content-Type': 'application/x-www-form-urlencoded'
        }).then(response => response.json())
            .then(answer => {
                if (myajax.current_page >= myajax.max_page)
                    morePosts.remove();
                appendNews(answer)
            });
    })

    function createPost(posts) {
        let imgContainer = createElem('div','wrapper-img',null,null);
        let postImg = createElem('img','post__img',[{'src':posts['thumb']}],null);
        imgContainer.appendChild(postImg);

        let contextData = createElem('div','wrapper-context',null,null);
        let postLink = createElem('a','title-link',[{'href':posts['guid']}],posts['post_title']);
        let postDate = createElem('div','wrapper-context-date',null,'Date ' + posts['post_date']);
        let postAuthor = createElem('div','wrapper-context-author',null,'Author ' + posts['post_author']);

        contextData.appendChild(postLink);
        contextData.appendChild(postDate);
        contextData.appendChild(postAuthor);

        let article = createElem('div','wrapper-post',null,null);
        article.appendChild(imgContainer);
        article.appendChild(contextData);

        return article;

    }

    function createElem(tag, className, attrs, content) {
        let elem = document.createElement(tag);

        if (className) elem.className = className;
        if (attrs) attrs.forEach(attr => elem.setAttribute(Object.keys(attr), Object.values(attr)));
        if (content) elem.innerHTML = content;

        return elem;
    }

    function appendNews(newsArr) {
        const newsList = document.querySelector('.wrapper');
        let newsListFragment = document.createDocumentFragment();
        newsArr.forEach(posts => newsListFragment.appendChild(createPost(posts)));
        newsList.appendChild(newsListFragment);

        myajax.current_page++;
        morePosts.disabled = false;
    }
}

loadMore();


let sortDown = document.querySelector('.sort-down');
let sortUp = document.querySelector('.sort-up');


function customSort(UpDown = false){

    let wrapper = document.querySelector('.wrapper');
    let posts = document.querySelectorAll('.wrapper-post');
    wrapper.innerHTML = '';

    posts = [...posts].sort((postA, postB)=>{
        let offset = 25; // TODO MAKE PRETTY
        let a = postA.querySelector('.title-link').textContent
        let b = postB.querySelector('.title-link').textContent

        if(a[offset] < b[offset]) { return UpDown? 1 : -1; }
        if(a[offset] > b[offset]) { return !UpDown? 1 : -1; }
        return 0;

    })
    console.log(posts)


    posts.forEach((el)=>{
        wrapper.appendChild(el);
    })
}

sortDown.addEventListener('click', ()=>{
    document.cookie = `sort= ; expires = Thu, 01 Jan 1970 00:00:00 GMT`
    customSort(false);

})


sortUp.addEventListener('click', ()=>{
    document.cookie = `sort= ; expires = Thu, 01 Jan 1970 00:00:00 GMT`
    customSort(true);

})


























