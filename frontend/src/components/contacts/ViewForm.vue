<template>
  <div class="contact-form-container">
    <div class="card">
      <div class="card-header">Contact Form Details</div>
      <div class="card-body">
        <div v-if="loading" class="text-center">
          Loading...
        </div>
        <div v-else>
          <div v-if="!form" class="alert alert-info small">
            Contact form not found.
          </div>
          <div v-else>
            <div class="mb-3">
              <label class="form-label">ID:</label>
              <div>{{ form.id }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Name:</label>
              <div>{{ form.name }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Email:</label>
              <div>{{ form.email }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Subject:</label>
              <div>{{ form.subject }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Message:</label>
              <div>{{ form.message }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Submitted At:</label>
              <div>{{ form.created_at }}</div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <router-link :to="'/contact/edit/' + form.id" class="btn btn-primary">Edit</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getFormById } from '@/services/api'; 

export default {
  data() {
    return {
      form: null,
      loading: false
    };
  },
  mounted() {
    const formId = this.$route.params.id; 
    if (formId) {
      this.fetchForm(formId);
    }
  },
  methods: {
    async fetchForm(id) {
      this.loading = true;
      try {
        const response = await getFormById(id); 
        this.form = response.data;
      } catch (error) {
        console.error('Error fetching form:', error);
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.contact-form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 70vh;
}

.card {
  width: 100%;
  max-width: 600px;
}

.card-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid rgba(0, 0, 0, 0.125);
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

.small {
  font-size: 0.875rem;
}
</style>
