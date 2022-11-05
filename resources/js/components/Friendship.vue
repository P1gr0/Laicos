<template>
    <div style="height: 60%">
        <h5 class="mb-0">{{ status }}</h5>
        <button v-if="status == 'Request received'" class="btn btn-custom tomato" @click="interact(true)">
            Accept
        </button>
        <button class="mt-1 btn btn-custom tomato" @click="interact(false)">
            {{ message }}
        </button>
        <div>
            <a v-if="status == 'Friend'" type="button" class="btn btn-bl tomato" href="/chat">
                Chat! <i style="color: rgb(157, 167, 173)" class="fa-solid fa-message fa"></i>
            </a>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: ['user_id'],
    data() {
        return { status: '', message: '' }
    },
    watch: {
        user_id: {
            handler: function () {
                this.getStatus();
            },
            deep: true,
            immediate: true
        }
    },
    methods: {
        getStatus() {
            axios.get('/getstatus/' + this.user_id).then((response) => {
                [this.status, this.message] = response.data;
            })
        },
        interact(accept) {
            if (accept) // accept friend request (update)
                axios.put('/friendship/' + this.user_id).then((response) => {
                    [this.status, this.message] = response.data;
                })
            else if (this.status == '') // send friend request (create)
                axios.post('/friendship/', { friend: this.user_id }).then((response) => {
                    [this.status, this.message] = response.data;
                })
            else // cancel sent request / decline received request / remove friend (delete)
                axios.delete('/friendship/' + this.user_id).then((response) => {
                    [this.status, this.message] = response.data;
                })
        }
    }
}
</script>

<style scoped>
.btn-custom {
    font-weight: bold;
    background-color: dodgerblue;
}
</style>

