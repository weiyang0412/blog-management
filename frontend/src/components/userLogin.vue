<template>
    <div class="login-container">
        <div class="card">
            <div class="card-header">Log In!</div>
            <div class="card-body">
                <form @submit.prevent="loginUser" novalidate>
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
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Login</button>
                        <router-link to="/register" class="btn btn-secondary">Register</router-link>
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
import { loginUser } from '@/services/api';

export default {
    data() {
        return {
            user: {
                email: '',
                password: ''
            },
            errors: {},
            message: ''
        };
    },
    methods: {
        validateForm() {
            this.errors = {};
            if (!this.user.email) {
                this.errors.email = 'Email is required.';
            }
            if (!this.user.password) {
                this.errors.password = 'Password is required.';
            }
            return Object.keys(this.errors).length === 0;
        },
        async loginUser() {
            if (this.validateForm()) {
                try {
                    console.log('Sending user data:', this.user);
                    const response = await loginUser(this.user);
                    console.log('Response data:', response.data);
                    this.message = 'Login successful!';
                    this.$emit('updateUser', response.data.user);
                    localStorage.setItem('loggedInUser', JSON.stringify(response.data.user));
                    console.log('loggedInUser', JSON.stringify(response.data.user))
                    // setTimeout(() => {
                    //     window.location.href = '/';
                    // }, 1000);
                    
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
                email: '',
                password: ''
            };
            this.errors = {};
        },
        goToRegister() {
            this.$router.push('/register');
        }
    }
};
</script>

<style scoped>
.login-container {
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