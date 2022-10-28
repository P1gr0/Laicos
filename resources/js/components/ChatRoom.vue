<template>
    <div class="container">
        <div class="card">
            <div class="card-header">Chats</div>
            <div class="card-body">
                <chat-messages :messages="messages"></chat-messages>
            </div>
            <div class="card-footer">
                <chat-form @messagesent="addMessage" :user="this.user"></chat-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            messages: undefined
        }
    },
    props: ['user'],
    mounted() {
        this.fetchMessages();
        console.log(window.Echo);
        console.log(window.Pusher);
        window.Echo.private('chat')
            .listen('MessageSent', (e) => {
                console.log(e)
                this.messages.push({
                    message: e.message.message,
                    user: e.user
                });
            });

    },
    methods: {
        fetchMessages() {
            //GET request to the messages route in our Laravel server to fetch all the messages
            axios.get('/messages').then(response => {
                //Save the response in the messages array to display on the chat view
                this.messages = response.data;
            });
        },
        //Receives the message that was emitted from the ChatForm Vue component
        addMessage(message) {
            //Pushes it to the messages array
            this.messages.push(message);
            //POST request to the messages route with the message data in order for our Laravel server to broadcast it.
            axios.post('/messages', message).then(response => {
                console.log(response.data);
            });
        }
    }
}
</script>