<template>
    <div class="menu-item" @click="isOpen = !isOpen">
        <a href="#">
            {{ title }}
        </a>
        <svg viewBox="0 0 1030 638" width="10">
            <path d="M1017 68L541 626q-11 12-26 12t-26-12L13
            68Q-3 49 6 24.5T39 0h952q24 0 33 24.5t-7 43.5z"
            fill="#FFF"></path>
        </svg>
        <transition name="fade" appear>
            <div class="sub-menu" v-if="isOpen">
                <div v-for="(item, i) in items" :key="i" class="menu-item" @click.stop="handleItemClick(item)">
                    <a href="#">{{ item.title }}</a>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    name: 'DropDown',
    props: ['title', 'items'],
    data () {
        return {
            isOpen: false
        }
    },
    methods: {
        handleItemClick(item) {
            this.$emit('item-click', item);
        }
    }
}
</script>

<style>
nav .menu-item svg {
    width: 15px;
    margin-left: 10px;
}

nav .menu-item .sub-menu {
    position: absolute;
    background-color: #222;
    top: calc(100% + 18px);
    left: 50%;
    transform: translate(-50%);
    width: 100%;
    border-radius: 0px 0px 16px 16px;
    
}

nav .menu-item .sub-menu a {
    color: inherit;
    text-decoration: none;
}

nav .menu-item a {
    color: inherit;
    text-decoration: none;
}

.fade-enter-active,
.fade-leave-active {
    transition: all .5s ease-out;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>