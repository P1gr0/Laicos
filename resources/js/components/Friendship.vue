<template>
    <div class="mb-7">
        <h5>{{ status }}</h5>
        <button class="btn btn-friend" v-if="status == 'Request received'" @click="interact(true)">
            Accept
        </button>
        <button class="btn btn-friend" @click="interact(false)">
            {{ message }}
        </button>
    </div>
</template>

<style scoped>
.btn-friend {
    color: white;
    background-color: dodgerblue;
    border: none;
    border-radius: 10%;
    margin: .5em;
}
</style>

<script>
import axios from 'axios';
export default {
    props: ['user_id'],
    data() {
        return {
            status: '',
            message: ''
        }
    },
    mounted() {
        this.getStatus();
    },
    methods: {
        getStatus() {
            axios.get('/getstatus/' + this.user_id).then((response) => {
                this.status = response.data;
                this.action(this.status);
            })
        },
        action(status) {
            if (status == 'Request sent') this.message = 'Cancel';
            else if (status == 'Request received') this.message = 'Remove';
            else if (status == 'Friend') this.message = 'Remove friend';
            else this.message = 'Add friend'
        },
        interact(accept) {
            if (accept) // accept friend request (update)
                axios.put('/friendship/' + this.user_id).then((response) => {
                    this.status = 'Friend';
                    this.action(this.status);
                })
            else if (this.status == '') // send friend request (create)
                axios.post('/friendship/', { friend: this.user_id }).then((response) => {
                    this.status = response.data;
                    this.action(this.status);
                })
            else // cancel sent request / decline received request / remove friend (delete)
                axios.delete('/friendship/' + this.user_id).then((response) => {
                    this.status = '';
                    this.action(this.status);
                })
        }
    }
}
</script>

