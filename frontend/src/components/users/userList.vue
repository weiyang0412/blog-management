<template>
    <div class="user-list-container">
        <h1 class="title">Manage Users</h1>
        <div class="container">
            <div class="links">
                <a style="color: black">Links</a>
                <router-link :to="{ path: '/user' }"
                    :class="{ 'active-link': $route.path === '/user', 'inactive-link': $route.path !== '/user' }">All
                    Users</router-link>
                <router-link :to="{ path: '/user/create' }"
                    :class="{ 'active-link': $route.path === '/user/create', 'inactive-link': $route.path !== '/user/create' }">New
                    User</router-link>
            </div>
            <div class="card-container">
                <div class="card">
                    <div class="users">
                        <div v-for="user in users" :key="user.id" class="user-card">
                            <div class="user-item">
                                <span class="user-name">{{ user.name }}</span>
                                <div class="actions">
                                    <button @click="editUser(user.id)" class="editButton">Edit</button>
                                    <button @click="deleteUser(user.id)" class="deleteButton">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { getUsers, deleteUser } from '@/services/api';

export default {
    data() {
        return {
            users: []
        };
    },
    methods: {
        fetchUsers() {
            getUsers().then(response => {
                this.users = response.data;
            });
        },
        editUser(id) {
            this.$router.push({ name: 'EditUser', params: { id } });
        },
        deleteUser(id) {
            deleteUser(id).then(() => {
                this.fetchUsers();
            });
        }
    },
    created() {
        this.fetchUsers();
    }
};
</script>

<style scoped>
.user-list-container {
    padding: 20px;
}

.title {
    display: inline-block;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 20px;
    margin-left: 25%;
    width: 50%;
}

.card-container {
    margin: 10px auto;
    padding: 10px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card {
    margin: 10px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.container {
    display: flex;
}

.links {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.links a {
    padding-left: 200px;
    text-decoration: none;
    /* color: blue; */
}

.users {
    display: flex;
    flex-direction: column;
}

.user-card {
    background-color: #fff;
    border-bottom: 1px solid #ddd;
    padding: 10px;
    margin: 5px;
    padding-left: 20px;
}

.user-item {
    display: flex;
    align-items: center;
    padding-left: 10px;
}

.user-name {
    flex-grow: 1;
    margin-right: 390px;
}

.actions {
    display: flex;
    gap: 10px;
}

.actions button {
    background-color: transparent;
    border: none;
    color: blue;
    cursor: pointer;
    width: 60px;
}

.actions .editButton {
    background-color: transparent;
    border: none;
    color: blue;
    cursor: pointer;
    /* margin-left: 280px; */
    /* width: 100px; */
}

.actions .deleteButton {
    background-color: transparent;
    border: none;
    color: grey;
    cursor: pointer;
    /* margin-left: 50px; */
}

.actions button:hover {
    /* text-decoration: underline; */
}

.user-card:last-child {
    border-bottom: none;
}

.active-link {
    color: blue;
}

.inactive-link {
    color: black;
}
</style>
