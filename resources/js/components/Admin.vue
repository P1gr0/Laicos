<template>
    <div v-if="errors" class="alert alert-danger card-footer my-0">
        <p class="my-0" v-for="error in errors">{{ error }}</p>
    </div>
    <div v-if="success" class="alert alert-success">
        <p class="mb-0">{{ $message }}</p>
    </div>
    <div class="w-100" v-for="user in users">
        <form @submit.prevent="editUser(user)">
            <div class="d-flex justify-content-between align-items-center">
                <p class="my-auto me-2">ID: {{ user.id }}</p>
                <input class="me-2" type="text" v-model="user.name" required>
                <input class="me-2" type="email" v-model="user.email" required>
                <input class="me-2" name="image" v-model="user.image">
                <button class="btn btn-warning mx-2" type="submit">Edit</button>
                <button @click="removeUser" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: '', success: '', errors: ''
        }
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        getUsers() {
            axios.get('/users').then((response) => {
                console.log(response.data);
                this.users = response.data
            })
        },
        removeUser() {
            if (confirm('User will be removed permanently along with messages, posts and comments. Are you sure?'))
                axios.delete("/users/" + this.id).then(() => {
                    this.users = this.users.filter(o => o.id !== e);
                });
        },
        editUser(user) {
            axios.put("/users/" + user.id, user).then((response) => {
                this.errors = '';
                this.success = response.data;
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.success = '';
            });;
        }
    }
}
</script>