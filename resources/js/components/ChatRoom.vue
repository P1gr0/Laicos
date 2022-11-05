<template>
    <div class="container">
        <div class="card">
            <div class="p-0 card-header d-flex justify-content-center">
                <select class="w-50 bg-white m-2" v-model="receiver_id">
                    <option value="" disabled>Choose friend</option>
                    <option class="py-0" v-for="friend in friends" :value="friend.id">
                        {{ friend.name }}
                    </option>
                </select>
            </div>
            <chat-messages v-if="receiver_id" :receiver_id="receiver_id" :user="user"></chat-messages>
        </div>
    </div>
</template>

<script>
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
export default {
    data() {
        return {
            user: undefined,
            receiver_id: "",
            friends: undefined
        }
    },
    props: ['userj'],
    mounted() {
        this.user = JSON.parse(`${this.userj}`);
        this.getFriends();
        window.Pusher = Pusher;
        Pusher.logToConsole = true;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https'
        })
        console.log(window.Echo);
    },
    methods: {
        getFriends() {
            if (!this.friends) // request only at first click
                axios.get('/friendship/' + this.user.id).then((response) => {
                    this.friends = response.data;
                });
        }
    }
}
</script>

<style scoped>
.card-header {
    background-color: #8AAFCC;
}
</style>