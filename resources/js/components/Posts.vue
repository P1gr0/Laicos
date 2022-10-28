<script>
export default {
    props: ['is_home', 'is_author', 'user_id', 'user_name', 'user_image'],
    data() { return { posts: [] } },
    mounted() { this.getPosts() },
    methods: {
        deletePost(e) { 
            this.posts = this.posts.filter(o => o.id !== e); 
        },
        getPosts() {
            axios.get('/getposts/' + this.user_id).then((response) => {
                this.posts = response.data;
            })
        },
        addPost(newPost) {
            this.posts.unshift(newPost);
        }
    }
} 
</script>

<template>
    <div>
        <create-post v-if="this.is_home" :user_name="this.user_name" @create-post="addPost"></create-post>
        <post v-for="post in posts" :title="post.title" :content="post.content" :key="post.id" :id="post.id"
            :created_at="post.created_at" :user_id="this.user_id" :user_name="this.user_name" :user_image="this.user_image" :image="post.image"
            :is_author="this.is_author" @delete-post="deletePost"></post>
    </div>
</template> 
    
