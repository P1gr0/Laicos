<script>
export default {
    props: ['is_home', 'is_author', 'userj'],
    data() { return { posts: [], user: '', page: 1, added: 0 } },
    mounted() {
        this.user = JSON.parse(`${this.userj}`);
        this.getPosts();
        window.addEventListener('scroll', this.onScroll);
    },
    methods: {
        deletePost(e) {
            this.posts = this.posts.filter(o => o.id !== e);
        },
        getPosts() {
            let offset = 0;
            if (this.added) {
                this.page +=  Math.floor(this.added / 10);
                offset = this.added % 10;
                this.added = 0;
            }
            axios.get('/getposts/' + this.user.id + '?page=' + this.page++).then((response) => {
                if (offset) response.data.data.splice(0, offset)
                if (!response.data.data.length) window.removeEventListener('scroll', this.onScroll);
                this.posts.push(...response.data.data);
                console.log(response.data.data);
            })
        },
        addPost(newPost) {
            this.added++;
            this.posts.unshift(newPost);
        },
        onScroll() {
            console.log((window.innerHeight + window.pageYOffset), document.body.offsetHeight)
            if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight - 1)
                this.getPosts();
        }
    }
} 
</script>

<template>
    <div>
        <create-post v-if="this.is_home" :user_name="this.user.name" @create-post="addPost"></create-post>
        <post v-for="post in posts" :title="post.title" :content="post.content" :key="post.id" :id="post.id"
            :created_at="post.created_at" :user="this.user" :image="post.image" :is_author="this.is_author"
            @delete-post="deletePost" :likes="post.likes" :liked="post.liked"></post>
    </div>
</template> 
    
