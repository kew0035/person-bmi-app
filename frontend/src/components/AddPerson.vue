<template>
  <section class="view-section">
    <h2>Add Person</h2>
    <form @submit.prevent="addPerson" class="form">
      <label>Fullname :</label>
      <input v-model="name" type="text" placeholder="Name" required />

      <label>Year Born :</label>
      <input v-model.number="yob" type="number" placeholder="yyyy" required />

      <label>Weight :</label>
      <input v-model.number="weight" type="number" placeholder="Weight (kg)" required />

      <label>Height :</label>
      <input v-model.number="height" type="number" placeholder="Height (cm)" required />

      <label>Photo URL :</label>
      <input v-model="photoUrl" type="text" placeholder="Image URL" />

      <button type="submit">Add Person</button>
    </form>
  </section>
</template>

<script>
import { ref } from 'vue';

export default {
  setup() {
    const name = ref('');
    const yob = ref(null);
    const weight = ref(null);
    const height = ref(null);
    const photoUrl = ref('');

    function addPerson() {
      if (!name.value || !yob.value || !weight.value || !height.value) {
        alert('Please fill in all required fields');
        return;
      }

      const age = new Date().getFullYear() - yob.value;
      const bmi = (weight.value / ((height.value / 100) ** 2)).toFixed(2);

      const personData = {
        name: name.value,
        yob: yob.value,
        age,
        weight: weight.value,
        height: height.value,
        bmi,
        photoUrl: photoUrl.value || null, 
      };

      fetch('http://localhost:8085/person', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(personData)
      })
      .then(async res => {
        if (!res.ok) {
          const errorMsg = await res.text();
          throw new Error(errorMsg || 'Failed to add person');
        }
        return res.json();
      })
      .then(data => {
        name.value = '';
        yob.value = null;
        weight.value = null;
        height.value = null;
        photoUrl.value = '';

        alert('Person added successfully! ID: ' + data.id);
      })
      .catch(err => {
        alert('Error: ' + err.message);
      });
    }

    return { name, yob, weight, height, photoUrl, addPerson };
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
</style>
