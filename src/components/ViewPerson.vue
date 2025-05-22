<template>
  <section class="view-section">
    <h2>View One Person</h2>
    <label>Enter Index (0-based):</label>
    <input type="number" v-model.number="viewIndex" :min="0" :max="persons.length - 1" placeholder="Enter index" />
    <div v-if="person" class="person-details-box">
      <img v-if="person.photoUrl" :src="person.photoUrl" alt="photo" class="person-photo-large" />
      <div class="person-info">
        <p><b>{{ person.name }} (Age: {{ person.age }})</b></p>
        <p><b>Weight:</b> {{ person.weight }} kg</p>
        <p><b>Height:</b> {{ person.height }} cm</p>
        <p><b>BMI:</b> {{ person.bmi }}</p>
      </div>

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

.person-details-box {
  display: flex;
  align-items: center;
  gap: 20px;
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
  margin-bottom: 0;
}

.person-info {
  font-size: 14px;
  color: #237a4f;
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
