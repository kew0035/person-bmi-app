<template>
  <section class="view-section">
    <h2>Person List</h2>
    <ul class="person-list">
      <li v-for="(p, i) in persons" :key="i" class="person-item">
        <img v-if="p.photoUrl" :src="p.photoUrl" alt="photo" class="person-photo" />
        <div class="person-info">
          <div class="person-name">[{{ i }}] {{ p.name }} <span>(Age: {{ p.age }})</span></div>
          <div class="person-details">Weight: {{ p.weight }} kg, Height: {{ p.height }} cm</div>
          <div class="person-bmi">BMI: {{ p.bmi }}</div>
        </div>
      </li>
    </ul>
  </section>
</template>

<script>
export default {
  props: {
    persons: { type: Array, required: true }
  }
};
</script>

<style scoped>
.person-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.person-item {
  display: flex;
  align-items: center;
  background-color: #e6f4ef;
  border-left: 6px solid #42b983;
  border-radius: 5px;
  padding: 10px 15px;
  margin-bottom: 15px;
}

.person-photo {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 15px;
  border: 2px solid #42b983;
}

.person-info {
  font-size: 14px;
  color: #237a4f;
}

.person-name {
  font-weight: 700;
  margin-bottom: 5px;
}

.person-name span {
  font-weight: 400;
  color: #555;
}

.person-details,
.person-bmi {
  margin-bottom: 3px;
}
</style>
