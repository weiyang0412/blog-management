<template>
    <div class="card" align="left">
        <div class="card-header">Login</div>
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
                <input type="submit" value="Login" class="btn btn-success" />
                <button class="btn btn-secondary" @click="goToRegister">Register</button>
            </form>
            <div v-if="message" class="mt-3 alert alert-info">{{ message }}</div>
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
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 1000);
                    
                } catch (err) {
                    console.error('Error:', err);
                    if (err.response && err.response.data) {
                        this.message = `Error: ${err.response.data.error}`;
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
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
}

.btn-secondary {
    margin-left: 10px;
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>