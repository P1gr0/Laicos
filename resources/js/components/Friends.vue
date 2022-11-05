<template>
    <div class="btn-group">
        <button type="button" @click="getFriends" class="btn btn-dark tomato dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            All friends
        </button>
        <ul class="dropdown-menu dropdown-menu-end friends-list">
            <li v-if="friends" class="dropdown-header text-center p-0">
                <h5>{{ friends.length }} Friends</h5>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li v-for="friend in friends" class="d-flex tomato">
                <img class="rounded-circle img-fluid mx-2"
                    :src="friend.image ? `/images/profile/${friend.image}` : `https://robohash.org/${friend.name}.png?set=set4`"
                    data-holder-rendered="true" width="30">
                <a class="dropdown-item px-0" :href="`/users/${friend.id}`">{{ friend.name }}</a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            friends: undefined
        }
    },
    props: ['user_id'],
    methods: {
        getFriends() {
            if (!this.friends) // request only at first click
                axios.get('/friendship/' + this.user_id).then(response => {
                    this.friends = response.data;
                });
        }
    }
}
</script>

<style scoped>
.friends-list {
    width: 50vw;
}
</style>