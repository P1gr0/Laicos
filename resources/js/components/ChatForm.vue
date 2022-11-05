<template>
    <div class="input-group">
        <input id="btn-input" type="text" class="form-control" placeholder="Type your message here..."
            v-model="newMessage" @keyup.enter="sendMessage" />
        <span class="input-group-btn">
            <button class="send tomato ms-0 mt-0 px-2" @click="sendMessage">Send</button>
        </span>
    </div>
</template>

<script>
export default {
    props: ["user", 'receiver_id'],
    data() {
        return {
            newMessage: "",
        }
    },
    methods: {
        sendMessage() {
            //Emit a "messagesent" event including the user who sent the message along with the message content
            this.$emit("messagesent", {
                user: this.user,
                receiver_id: this.receiver_id,
                message: this.newMessage
            })
            //Clear the input
            this.newMessage = "";
        }
    }
}
</script>

<style scoped>
.send {
    background-color: #8AAFCC;
    height: 100%;
    width: 100%;
    border-style: none;
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
}
</style>