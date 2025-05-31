<template>
  <section class="view-section">
    <h2>Delete Person</h2>
    <label>Enter Index (0-based):</label>
    <input type="number" v-model.number="deleteIndex" :min="0" :max="persons.length - 1" placeholder="Enter index to delete" />
    <button @click="deletePerson">Delete</button>
    <p class="feedback" v-if="deleteFeedback">{{ deleteFeedback }}</p>
  </section>
</template>

<script>
import { ref } from 'vue';

export default {
  props: {
    persons: { type: Array, required: true }
  },
  emits: ['delete-person'],
  setup(props, { emit }) {
    const deleteIndex = ref(null);
    const deleteFeedback = ref('');

    async function deletePerson() {
      if (
        deleteIndex.value === null ||
        deleteIndex.value < 0 ||
        deleteIndex.value >= props.persons.length
      ) {
        deleteFeedback.value = 'Invalid index!';
        return;
      }

      const personId = props.persons[deleteIndex.value]?.id;
      if (!personId) {
        deleteFeedback.value = 'Person ID not found!';
        return;
      }

      try {
        const res = await fetch(`http://localhost:8085/person/${personId}`, {
          method: 'DELETE'
        });

        const data = await res.json();

        if (!res.ok) {
          throw new Error(data.error || 'Delete failed');
        }

        deleteFeedback.value = data.message || 'Person deleted successfully';
        emit('delete-person', deleteIndex.value);
        deleteIndex.value = null;
      } catch (err) {
        deleteFeedback.value = 'Error: ' + err.message;
      }
    }

    return { deleteIndex, deleteFeedback, deletePerson };
  }
};
</script>

<style scoped>
label {
  font-weight: 600;
  display: block;
  margin-bottom: 6px;
}

input {
  width: 100%;
  padding: 8px 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
  box-sizing: border-box;
}

button {
  background-color: #42b983;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #237a4f;
}

.feedback {
  margin-top: 10px;
  color: #d32f2f;
  font-style: italic;
}
</style>
