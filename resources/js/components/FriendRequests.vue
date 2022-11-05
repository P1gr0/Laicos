<template>
    <button class="btn-dodg tomato" data-bs-toggle="modal" data-bs-target="#req" aria-expanded="false">
        <i class="fa-solid fa-people-pulling fa-xl">
            <span v-if="requests.length" class="counter-lg">{{ requests.length }}</span>
        </i>
    </button>

    <div class="modal fade" id="req" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Friends request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <p class="text-center" v-if="!requests.length">No pending friend requests</p>
                    <ul class="list-group">
                        <li v-for="request in requests" class="d-flex tomato list-group-item">
                            <img class="rounded-circle img-fluid me-2"
                                :src="request.image ? `/images/profile/${request.image}` : `https://robohash.org/${request.name}.png?set=set4`"
                                data-holder-rendered="true" width="30">
                            <a class="px-0 fs-5" :href="`/users/${request.id}`">{{ request.name }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            requests: []
        }
    },
    mounted() {
        this.getRequests();
    },
    methods: {
        getRequests() {
            axios.get('/friendship').then(response => {
                this.requests = response.data;
            })
        }
    }
}
</script>

<style scoped>
.btn-dodg {
    color: dodgerblue;
    cursor: pointer;
    border: none;
}

.counter-lg {
    color: tomato;
    position: relative;
    right: 0em;
    bottom: 1em;
    font-size: 0.5em;
    font-weight: bolder;
}
</style>