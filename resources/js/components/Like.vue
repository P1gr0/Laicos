<template>
    <div class="tomato" :class="liked_up ? 'liked' : ''" @click="toggle()">
        <p class="my-0">
            <i class="fa-regular fa-heart"></i> {{ likes_up }}
        </p>
    </div>
</template>

<style scoped>
.liked {
    color: crimson;
}
</style>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            likes_up: undefined,
            liked_up: undefined
        }
    },
    mounted() {
        this.likes_up = this.likes;
        this.liked_up = this.liked;
    },
    props: ['likes', 'liked', 'baseUrl', 'id'],
    methods: {
        toggle() {
            axios.post(this.baseUrl + '/togglelike/' + this.id).then((response) => {
                if (this.liked_up) this.likes_up--;
                else this.likes_up++;
                this.liked_up = !this.liked_up;
            });
        }
    }
}
</script>