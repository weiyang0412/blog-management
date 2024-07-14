<template>
    <div class="contact-list-container">
      <div class="card">
        <div class="card-header">Contact Form Submissions</div>
        <div class="card-body">
          <div v-if="loading" class="text-center">
            Loading...
          </div>
          <div v-else>
            <div v-if="forms.length === 0" class="alert alert-info small">
              No contact forms submitted yet.
            </div>
            <div v-else>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="form in forms" :key="form.id">
                    <th scope="row">{{ form.id }}</th>
                    <td>{{ form.name }}</td>
                    <td>{{ form.email }}</td>
                    <td>{{ form.subject }}</td>
                    <td>
                      <button @click="viewContact(form.id)" class="btn btn-sm btn-info me-1">View</button>
                      <button @click="deleteForm(form.id)" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
<script>
import { getAllForms, getUserForms, deleteForm } from '@/services/api'; 
import { isLoggedIn, getLoggedInUser } from '@/services/auth';

export default {
  data() {
    return {
      forms: [],
      loading: false,
      user: null,
    };
  },
  async mounted() {
    if (isLoggedIn()) {
      this.user = getLoggedInUser();
      await this.fetchForms();
    } else {
      // Redirect to login if not logged in
      this.$router.push('/login');
    }
  },
  methods: {
    async fetchForms() {
      this.loading = true;
      try {
        if (this.user && this.user.role === 'admin') {
          const response = await getAllForms(); 
          this.forms = response.data;
        } else {
          const response = await getUserForms(this.user.id); 
          this.forms = response.data;
        }
      } catch (error) {
        console.error('Error fetching forms:', error);
      } finally {
        this.loading = false;
      }
    },
    async deleteForm(formId) {
      if (confirm('Are you sure you want to delete this form?')) {
        try {
          await deleteForm(formId); 
          this.forms = this.forms.filter(form => form.id !== formId);
          alert('Form deleted successfully.');
        } catch (error) {
          console.error('Error deleting form:', error);
        }
      }
    },
    viewContact(id) {
        this.$router.push({ name: 'ViewForm', params: { id } });
    },
  }
};
</script>
<style scoped>
.contact-list-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 70vh;
}

.card {
  width: 100%;
  max-width: 800px;
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

.table {
  margin-top: 1rem;
}

.small {
  font-size: 0.875rem;
}
</style>
  