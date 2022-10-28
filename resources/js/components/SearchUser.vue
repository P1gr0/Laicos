<template>
    <div class="bg-dark">
    <form class="py-2 d-flex m-auto bar" :action="`/search/${name}`">
        <input class="form-control me-2" type="search" placeholder="Search user" v-model="name" aria-label="Search">
        <button class="btn-custom tomato" type="submit" @click="search"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
</div>
</template>

<script>
export default{
    data() {
        return {
            name: ''
        }
    }
}
</script>

<style scoped>
.bar{
    width: 50vw;
    min-width: 340px;
}
</style>