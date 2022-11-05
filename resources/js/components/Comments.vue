<script>
export default {
    props: ['post_id'],
    data() { return { comments: [], page: 1, added: 0 } },
    mounted() {
        this.getComments(),
            window.addEventListener('scroll', this.onScroll)
    },
    methods: {
        deleteComment(e) {
            this.comments = this.comments.filter(o => o.id !== e);
        },
        getComments() {
            let offset = 0;
            if (this.added) {
                this.page += Math.floor(this.added / 15);
                offset = this.added % 15;
                this.added = 0;
            }
            axios.get(this.post_id + '/comments?page=' + this.page++).then((response) => {
                if (offset) response.data.data.splice(0, offset)
                if (!response.data.data.length) window.removeEventListener('scroll', this.onScroll);
                this.comments.push(...response.data.data);
            })
        },
        addComment(newComment) {
            this.added++;
            this.comments.unshift(newComment);
        },
        onScroll() {
            if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight - 1)
                this.getComments();
        }
    }
} 
</script>

<template>
    <div>
        <create-comment @create-comment="addComment" :post_id="this.post_id"></create-comment>
        <comment v-for="comment in comments" :content="comment.content" :key="comment.id" :id="comment.id"
            :created_at="comment.created_at" :image="comment.image" :user="comment.user" :is_author="comment.is_author"
            :likes="comment.likes" :liked="comment.liked" @delete-comment="deleteComment"></comment>
    </div>
</template> 
    