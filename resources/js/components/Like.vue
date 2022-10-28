<template>
    <div class="tomato" :class="liked ? 'liked' : '' " 
     @click="toggle()">
            <p class="my-0">
                <i class="fa-regular fa-heart"></i> {{ likes }}
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
            likes: 0,
            liked: false
        }
    },
    mounted() {
        this.getLikes();
    },
    props: ['id', 'baseUrl'],
    methods: {
        getLikes() {
            axios.get(this.baseUrl + "/getlikes/" + this.id).then((response) => {
                this.likes = response.data[0];
                this.liked = response.data[1];
            });
        },
        toggle() {
            axios.post(this.baseUrl + '/togglelike/' + this.id).then((response) => {
                if(this.liked) this.likes--;
                else this.likes++;
                this.liked = !this.liked;
            });
        }
    }
}
</script>