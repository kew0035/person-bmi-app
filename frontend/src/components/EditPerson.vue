<template>
  <section class="view-section">
    <h2>Edit Person</h2>
    <label>Enter Index (0-based):</label>
    <input type="number" v-model.number="editIndex" :min="0" :max="persons.length - 1" placeholder="Enter index" @change="loadPersonToEdit" />

    <div v-if="personToEdit" class="form">
      <label>Name:</label>
      <input v-model="editName" type="text" />

      <label>Year Born:</label>
      <input v-model.number="editYob" type="number" />

      <label>Weight (kg):</label>
      <input v-model.number="editWeight" type="number" />

      <label>Height (cm):</label>
      <input v-model.number="editHeight" type="number" />

      <label>Photo URL:</label>
      <input v-model="editPhotoUrl" type="text" />

      <button @click="updatePerson">Update</button>
    </div>

    <p v-else>Enter a valid index to load person data.</p>
  </section>
</template>

<script>
import { ref } from 'vue';

export default {
  props: {
    persons: { type: Array, required: true }
  },
  emits: ['update-person'],
  setup(props, { emit }) {
    const editIndex = ref(null);
    const personToEdit = ref(null);
    const editName = ref('');
    const editYob = ref(null);
    const editWeight = ref(null);
    const editHeight = ref(null);
    const editPhotoUrl = ref('');

    async function loadPersonToEdit() {
      if (editIndex.value === null || editIndex.value < 0 || editIndex.value >= props.persons.length) {
        personToEdit.value = null;
        return;
      }

      const personId = props.persons[editIndex.value]?.id;
      if (!personId) {
        alert('Invalid person id');
        return;
      }

      try {
        const res = await fetch(`http://localhost:8085/person/${personId}`);
        if (!res.ok) {
          throw new Error('Failed to fetch person data');
        }

        const data = await res.json();
        personToEdit.value = data;
        editName.value = data.name;
        editYob.value = data.yob;
        editWeight.value = data.weight;
        editHeight.value = data.height;
        editPhotoUrl.value = data.image; 
      } catch (error) {
        console.error(error);
        alert('Error loading person data');
      }
    }

    async function updatePerson() {
      if (!personToEdit.value) return;
      if (!editName.value || !editYob.value || !editWeight.value || !editHeight.value) {
        alert('Please fill all required fields');
        return;
      }
      const age = new Date().getFullYear() - editYob.value;
      const bmi = (editWeight.value / ((editHeight.value / 100) ** 2)).toFixed(2);
      const updatedPerson = {
        name: editName.value,
        yob: editYob.value,
        age,
        weight: editWeight.value,
        height: editHeight.value,
        bmi,
        photoUrl: editPhotoUrl.value,
      };

      const personId = props.persons[editIndex.value]?.id;
        if (!personId) {
          alert('Invalid person id');
          return;
        }

        try {
          const res = await fetch(`http://localhost:8085/person/${personId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updatedPerson)
          });

          if (!res.ok) {
            const errText = await res.text();
            throw new Error(errText || 'Update failed');
          }

          emit('update-person', { index: editIndex.value, person: updatedPerson });
          alert('Person updated!');
        } catch (err) {
          alert('Error: ' + err.message);
        }
      }

    return {
      editIndex, personToEdit,
      editName, editYob, editWeight, editHeight, editPhotoUrl,
      loadPersonToEdit, updatePerson
    };
  }
};
</script>

<style scoped>
.form {
  max-width: 400px;
  margin-top: 15px;
}

label {
  display: block;
  font-weight: 600;
  margin: 8px 0 4px 0;
}

input {
  width: 100%;
  padding: 8px 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
  box-sizing: border-box;
}

button {
  background-color: #42b983;
  color: white;
  padding: 10px 18px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
  font-size: 16px;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #237a4f;
}

p {
  margin-top: 10px;
  color: #d32f2f;
  font-style: italic;
}
</style>
