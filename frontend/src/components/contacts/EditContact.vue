<template>
    <div class="contact-edit-container">
        <div class="card">
            <div class="card-header">Edit Contact</div>
            <div class="card-body">
                <form @submit.prevent="updateForm" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" v-model="contact.name" name="name" id="name" class="form-control"
                            :class="{ 'is-invalid': errors.name }" required />
                        <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" v-model="contact.email" name="email" id="email" class="form-control"
                            :class="{ 'is-invalid': errors.email }" required />
                        <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" v-model="contact.subject" name="subject" id="subject" class="form-control"
                            :class="{ 'is-invalid': errors.subject }" required />
                        <div v-if="errors.subject" class="invalid-feedback">{{ errors.subject }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea v-model="contact.message" name="message" id="message" class="form-control"
                            :class="{ 'is-invalid': errors.message }" rows="4" required></textarea>
                        <div v-if="errors.message" class="invalid-feedback">{{ errors.message }}</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
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
import { getContactById, updateContact } from '@/services/api'; 

export default {
    data() {
        return {
            contact: {
                name: '',
                email: '',
                subject: '',
                message: ''
            },
            message: '',
            errors: {}
        };
    },
    async created() {
        const contactId = this.$route.params.id;
        try {
            const response = await getContactById(contactId);
            this.contact = response.data;
        } catch (error) {
            console.error('Error fetching contact:', error);
            this.message = 'Failed to load contact data.';
        }
    },
    methods: {
        validateForm() {
            this.errors = {};
            if (!this.contact.name) {
                this.errors.name = 'Name is required.';
            }
            if (!this.contact.email) {
                this.errors.email = 'Email is required.';
            } else if (!this.isValidEmail(this.contact.email)) {
                this.errors.email = 'Invalid email format.';
            }
            if (!this.contact.subject) {
                this.errors.subject = 'Subject is required.';
            }
            if (!this.contact.message) {
                this.errors.message = 'Message is required.';
            }
            return Object.keys(this.errors).length === 0;
        },
        isValidEmail(email) {
            // Basic email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        async updateForm() {
            if (this.validateForm()) {
                try {
                    console.log('Updating contact data:', this.contact);
                    const response = await updateContact(this.contact.id, this.contact); 
                    console.log('Response:', response.data);
                    this.message = 'Form updated successfully!';
                    setTimeout(() => {
                        this.$router.push('/contact/list');
                    }, 2000);
                } catch (error) {
                    console.error('Error:', error);
                    if (error.response && error.response.data) {
                        this.message = error.response.data.error;
                    } else {
                        this.message = 'An error occurred while updating the form.';
                    }
                }
            }
        }
    }
};
</script>
<style scoped>
.contact-edit-container {
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

.btn-primary {
    margin-top: 10px;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

.message-container {
    margin-top: 1em;
}

.small {
    font-size: 0.875rem;
}
</style>
