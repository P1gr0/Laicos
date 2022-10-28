<template>
    <div class="col-1 dropdown">
        <button class="px-2 text-center" id="author-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-lg fa-ellipsis-vertical"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-dark">
            <li>
                <a class="dropdown-item tomato" @click="$emit('update')">
                    <i style="color: goldenrod" class="fa-solid fa-pen"></i>
                    Update <slot></slot>
                </a>
            </li>
            <li>
                <a class="dropdown-item tomato" @click="$emit('remove')">
                    <i style="color: crimson" class="fa-solid fa-trash-can"></i>
                    Remove <slot></slot>
                </a>
            </li>
        </ul>
    </div>
</template>

<style scoped>
#author-menu {
    all: unset;
    cursor: pointer;
}
</style>