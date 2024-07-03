<template>
    <nav>
        <div class="menu-item"><a><router-link to="/">Home</router-link></a></div>
        <div class="menu-item"><a v-if="isAdmin"><router-link to="/user-list">User</router-link></a></div>
        <div class="menu-item"><a v-if="isAdmin"><router-link to="/post">BLOG</router-link></a></div>
        <div class="dropdown-container">
            <Dropdown v-if="loggedInUser" :title="userName" :items="services" @item-click="handleDropdownClick" />
            <div v-else class="menu-item">
                <a><router-link to="/login">Login</router-link></a>
            </div>
        </div>
    </nav>
</template>

<script>
import Dropdown from './Dropdown.vue';

export default {
    name: 'TopNavbar',
    props: {
        isLoggedIn: {
            type: Boolean,
            required: true
        },
        user: {
            type: Object,
            default: null
        }
    },
    components: {
        Dropdown
    },
    data() {
        return {
            users: JSON.stringify(this.user || {}),
            searchQuery: '',
            services: [
                // {
                //     title: 'example',
                //     action: ''
                // },
                {
                    title: 'Logout',
                    action: 'logout'
                }
            ],
        };
    },
    computed: {
        loggedInUser() {
            return !!this.user;
        },
        userName() {
            return this.user ? this.user.name : '';
        },
        isAdmin() {
            let parsedUser;
            const loggedInUser = localStorage.getItem('loggedInUser');
            console.log('loggedInUser', loggedInUser);
            try {
                parsedUser = JSON.parse(loggedInUser);
                console.log(parsedUser)
            } catch (e) {
                console.error("Failed to parse users:", e);
                return false;
            }

            return this.user && parsedUser.role === 'admin';
        }
    },
    methods: {
        find() {
            console.log('Searching for:', this.searchQuery);
        },
        logout() {
            localStorage.removeItem('token');
            this.$emit('logout');
            this.$router.push('/login');
        },
        handleDropdownClick(item) {
            console.log('Dropdown item clicked:', item); // 输出点击的item信息
            if (item.action === 'logout') {
                console.log('Executing logout action'); // 确认是否执行了logout
                this.logout();
            } else {
                console.log('Clicked on:', item.title); // 输出其他项的点击信息
            }
        }
    }
};
</script>

<style>
nav {
    display: flex;
    align-items: center;
    justify-content: center;
}

nav .menu-item {
    color: #FFF;
    padding: 10px 20px;
    position: relative;
    text-align: center;
    border-bottom: 3px solid transparent;
    display: flex;
    transition: 0.4s;
}

nav .menu-item.active,
nav .menu-item:hover {
    background-color: #444;
    border-bottom-color: #FF5858;

}

nav .menu-item a {
    color: inherit;
    text-decoration: none;
}

.dropdown-container {
    margin-left: 1440px;
    padding-right: 10px;
}
</style>
