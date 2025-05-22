<template>
  <section class="view-section">
    <h2>View One Person</h2>
    <label>Enter Index (0-based):</label>
    <input type="number" v-model.number="viewIndex" :min="0" :max="persons.length - 1" placeholder="Enter index" />
    <div v-if="person" class="person-details-box">
      <img v-if="person.photoUrl" :src="person.photoUrl" alt="photo" class="person-photo-large" />
      <h3>{{ person.name }} (Age: {{ person.age }})</h3>
      <p>Weight: {{ person.weight }} kg</p>
      <p>Height: {{ person.height }} cm</p>
      <p>BMI: {{ person.bmi }}</p>
    </div>
    <p v-else>No person found at index {{ viewIndex }}</p>
  </section>
</template>

<script>
import { ref, computed } from 'vue';

export default {
  props: {
    persons: { type: Array, required: true }
  },
  setup(props) {
    const viewIndex = ref(null);
    const person = computed(() => {
      if (viewIndex.value === null) return null;
      return props.persons[viewIndex.value] || null;
    });
    return { viewIndex, person };
  }
};
</script>

<style scoped>
.person-details-box {
  margin-top: 15px;
  padding: 15px;
  background-color: #e6f4ef;
  border-left: 6px solid #42b983;
  border-radius: 5px;
}

.person-photo-large {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #42b983;
  margin-bottom: 10px;
}

h3 {
  color: #237a4f;
  margin-bottom: 8px;
}

p {
  margin: 3px 0;
  font-size: 15px;
  color: #237a4f;
}
</style>
