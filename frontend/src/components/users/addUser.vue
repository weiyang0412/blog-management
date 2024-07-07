<template>
    <div class="post-list-container">
        <h1 class="title">Add New User</h1>
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
                    <div class="posts">
                        <form @submit.prevent="saveUser" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Name</label>
                                <input v-model="form.name" class="form-control" placeholder="Name" required />
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input v-model="form.email" class="form-control" placeholder="Email" required />
                            </div>
                            <div class="mb-3">
                                <label>Role</label>
                                <select v-model="form.role" class="form-control" required>
                                    <option value="">Select a role</option>
                                    <option value="admin">Admin</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="participant">Participant</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input v-model="form.password" type="password" class="form-control" placeholder="Password" required />
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { createUser, updateUser } from '@/services/api';

export default {
    data() {
        return {
            form: {
                id: null,
                name: '',
                email: '',
                password: '',
                role: ''
            }
        };
    },
    methods: {
        async saveUser() {
            if (this.form.id) {
                try {
                    const response = await updateUser(this.form.id, this.form);
                    console.log('User updated:', response.data);
                    this.$router.push('/user');
                } catch (error) {
                    console.error('Error updating user:', error);
                }
            } else {
                try {
                    const response = await createUser(this.form);
                    console.log('User created:', response.data);
                    this.$router.push('/user');
                } catch (error) {
                    console.error('Error creating user:', error);
                }
            }
        },
        resetForm() {
            this.form = {
                id: null,
                name: '',
                email: '',
                password: '',
                role: ''
            };
        }
    }
};
</script>

<style scoped>
.post-list-container {
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
    padding: 50px;
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
}

.posts form {
    padding: 10px;
}

.form-control {
    width: 511px;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.btn-success {
    background-color: #5cb85c;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-success:hover {
    background-color: #4cae4c;
}

select.form-control {
    width: 100%;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.active-link {
    color: blue;
}

.inactive-link {
    color: black;
}
</style>