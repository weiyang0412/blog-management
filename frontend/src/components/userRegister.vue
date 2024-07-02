<template>

    <div class="card" align="left">
        <div class="card-header">Register Form</div>
        <div class="card-body">

            <form @submit.prevent="registerUser">

                <label>Name</label>
                <input type="text" v-model="user.name" name="name" id="name" class="form-control" required />

                <label>Email</label>
                <input type="email" v-model="user.email" name="email" id="email" class="form-control" required />

                <label>Password</label>
                <input type="password" v-model="user.password" name="password" id="password" class="form-control"
                    required />

                <label>Role</label>
                <select v-model="user.role" name="role" id="role" class="form-control" required>
                    <option value="">Select a role</option>
                    <option value="staff">Staff</option>
                    <option value="student">Student</option>
                </select>
                <br>
                <input type="submit" value="Register" class="btn btn-success">
            </form>

            <div v-if="message" class="mt-3 alert alert-info">{{ message }}</div>
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
            message: ''
        };
    },
    methods: {
        async registerUser() {
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
                    this.message = `Error: ${err.response.data.error}`;
                } else {
                    this.message = 'An unknown error occurred.';
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
        }
    }
};
</script>

<style scoped>
form {
    display: flex;
    flex-direction: column;
}

div {
    margin-bottom: 1em;
}

label {
    margin-bottom: 0.5em;
}

input {
    padding: 0.5em;
    font-size: 1em;
}

button {
    padding: 0.5em 1em;
    font-size: 1em;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

div.message {
    margin-top: 1em;
    font-size: 1.2em;
}

/* .btn.btn-success {
    margin-bottom: 5em;
} */
</style>