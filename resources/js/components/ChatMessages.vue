<template>
    <div ref="scrollable" class="card-body cont">
        <div class="d-flex" :class="message.user_id == this.user.id ? 'justify-content-end' : ''"
            v-for="message in messages" :key="message.id">
            <div class="m-1 p-0 card message shadow-lg">
                <div class="card-body py-2" :class="message.user_id == this.user.id ? 'sender' : 'receiver'">
                    <small class="fst-italic">{{ getTimeFromNow(message.created_at) }}</small>
                    <p class="mb-1">{{ message.message }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <chat-form @messagesent="addMessage" :receiver_id="this.receiver_id" :user="this.user"></chat-form>
    </div>
</template>

<script>
import * as utils from '../utils';
export default {
    data() {
        return {
            messages: undefined
        }
    },
    props: ["receiver_id", "user"],
    watch: {
        receiver_id: {
            handler: function () {
                this.fetchMessages(this.receiver_id);
            },
            deep: true,
            immediate: true
        }
    },
    mounted() {
        window.Echo.private('chat.' + this.user.id)
            .listen('MessageSent', (e) => {
                //check sender before pushing the message
                if (e.user.id == this.receiver_id)
                    this.messages.push(e.message);
            })
    },
    methods: {
        fetchMessages(receiver_id) {
            axios.get('/messages/' + receiver_id).then(response => {
                this.messages = response.data;
            }).then(() => this.scrollToBottom());
        },
        scrollToBottom() {
            if (this.$refs.scrollable)
                this.$refs.scrollable.scrollTop = this.$refs.scrollable.scrollHeight;
        },
        addMessage(message) {
            axios.post('/messages', message).then(response => {
                this.messages.push(response.data);
            }).then(() => this.scrollToBottom());
        },
        getTimeFromNow(creationDate) {
            return utils.timeSince(new Date(creationDate));
        }
    }
}
</script>

<style scoped>
.sender {
    border-radius: 7%;
    padding-left: 3em;
    background-color: rgba(183, 146, 194, 0.562);
}

.receiver {
    padding-right: 3em;
    background-color: rgba(65, 155, 246, 0.57);
    border-radius: 7%;
}

.cont {
    height: 70vh;
    overflow-y: scroll;
    background: linear-gradient(90deg, rgba(255, 200, 124, 0.714) 0%, rgba(252, 252, 121, 0.703) 90%);
}

.message {
    max-width: 75%;
    background: transparent;
    border-radius: 7%;
}

.card-footer {
    background-color: #D5C0B3;
}
</style>