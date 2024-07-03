<template>
    <div class="container">
        <h1 class="my-4">User List</h1>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2" @click="editUser(user.id)">Edit</button>
                        <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <form @submit.prevent="saveUser" class="mt-4">
            <div class="mb-3">
                <input v-model="form.name" class="form-control" placeholder="Name" required />
            </div>
            <div class="mb-3">
                <input v-model="form.email" class="form-control" placeholder="Email" required />
            </div>
            <div class="mb-3">
                <input v-model="form.role" class="form-control" placeholder="Role" required />
            </div>
            <div class="mb-3">
                <input v-model="form.password" type="password" class="form-control" placeholder="Password" required />
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</template>

<script>
import { getUsers, createUser, updateUser, deleteUser } from '@/services/api';

export default {
    data() {
        return {
            users: [],
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
        fetchUsers() {
            getUsers().then(response => {
                this.users = response.data;
            });
        },
        async saveUser() {
            if (this.form.id) {
                updateUser(this.form.id, this.form).then(() => {
                    this.fetchUsers();
                    this.resetForm();
                });
            } else {
                try {
                    console.log('Sending user data:', this.form);
                    const response = await createUser(this.form);
                    console.log('Response data:', response.data);
                    this.message = 'User registered successfully!';
                    this.resetForm();
                    window.location.reload();
                } catch (err) {
                    if (err.response && err.response.data) {
                        this.message = `Error: ${err.response.data.error}`;
                    } else {
                        this.message = 'An unknown error occurred.';
                    }
                }
            }
        },
        editUser(id) {
            const user = this.users.find(user => user.id === id);
            this.form = { ...user };
        },
        deleteUser(id) {
            deleteUser(id).then(() => {
                this.fetchUsers();
            });
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
    },
    created() {
        this.fetchUsers();
    }
};
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 8px;
    border: 1px solid #ddd;
}

th {
    background-color: #f4f4f4;
}
</style>