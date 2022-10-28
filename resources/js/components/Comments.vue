<script>
export default {
    props: ['post_id'],
    data() { return { comments: [] } },
    mounted() { this.getComments() },
    methods: {
        deleteComment(e) { 
            this.comments = this.comments.filter(o => o.id !== e); 
        },
        getComments() {
            axios.get(this.post_id + '/comments').then((response) => {
                this.comments = response.data;
            })
        },
        addComment(newComment) {
            this.comments.unshift(newComment);
        }
    }
} 
</script>

<template>
    <div>
        <create-comment @create-comment="addComment" :post_id="this.post_id"></create-comment>
        <comment v-for="comment in comments" :content="comment.content" :key="comment.id" :id="comment.id"
            :created_at="comment.created_at" :user="comment.user" :user_image="comment.user_image" :image="comment.image"
            :is_author="comment.is_author" @delete-comment="deleteComment"></comment>
    </div>
</template> 
    