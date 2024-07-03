<template>
    <div class="register-container">
        <div class="card">
            <div class="card-header">Register Form</div>
            <div class="card-body">
                <form @submit.prevent="registerUser" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" v-model="user.name" name="name" id="name" class="form-control"
                            :class="{ 'is-invalid': errors.name }" required />
                        <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" v-model="user.email" name="email" id="email" class="form-control"
                            :class="{ 'is-invalid': errors.email }" required />
                        <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" v-model="user.password" name="password" id="password" class="form-control"
                            :class="{ 'is-invalid': errors.password }" required />
                        <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select v-model="user.role" name="role" id="role" class="form-control"
                            :class="{ 'is-invalid': errors.role }" required>
                            <option value="">Select a role</option>
                            <option value="teacher">Teacher</option>
                            <option value="participant">Participant</option>
                        </select>
                        <div v-if="errors.role" class="invalid-feedback">{{ errors.role }}</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Register</button>
                    </div>
                </form>
                <div v-if="message" class="message-container">
                    <div class="alert alert-info small">{{ message }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { createUser } from '@/services/api';

export default {
    data() {
        return {
            user: {
                name: '',
                email: '',
                password: '',
                role: ''
            },
            message: '',
            errors: {}
        };
    },
    methods: {
        validateForm() {
            this.errors = {};
            if (!this.user.name) {
                this.errors.name = 'Name is required.';
            }
            if (!this.user.email) {
                this.errors.email = 'Email is required.';
            }
            if (!this.user.password) {
                this.errors.password = 'Password is required.';
            }
            if (!this.user.role) {
                this.errors.role = 'Role is required.';
            }
            return Object.keys(this.errors).length === 0;
        },
        async registerUser() {
            if (this.validateForm()) {
                try {
                    console.log('Sending user data:', this.user);
                    const response = await createUser(this.user);
                    console.log('Response data:', response.data);
                    this.message = 'User registered successfully!';
                    this.resetForm();
                    setTimeout(() => {
                        this.$router.push('/login');
                    }, 2000);
                } catch (err) {
                    console.error('Error:', err);
                    if (err.response && err.response.data) {
                        this.message = `${err.response.data.error}`;
                    } else {
                        this.message = 'An unknown error occurred.';
                    }
                }
            }
        },
        resetForm() {
            this.user = {
                name: '',
                email: '',
                password: '',
                role: ''
            };
            this.errors = {};
        }
    }
};
</script>

<style scoped>
.register-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 70vh;
}

.card {
    width: 100%;
    max-width: 400px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0,0,0,.125);
    text-align: center;
    font-size: 1.25rem;
    font-weight: bold;
}

.card-body {
    padding: 1.25rem;
}

.form-label {
    font-weight: bold;
}

.btn-secondary {
    margin-top: 10px;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.message-container {
    margin-top: 1em;
}

.small {
    font-size: 0.875rem;
}
</style>