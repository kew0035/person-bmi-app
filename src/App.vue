<template>
  <div class="container">
    <h1>Person BMI Web App</h1>

    <nav class="main-nav">
      <router-link to="/add" class="nav-link" active-class="active">Add Person</router-link> |
      <router-link to="/list" class="nav-link" active-class="active">View All</router-link> |
      <router-link to="/view" class="nav-link" active-class="active">View One</router-link> |
      <router-link to="/edit" class="nav-link" active-class="active">Edit</router-link> |
      <router-link to="/delete" class="nav-link" active-class="active">Delete</router-link> |
      <router-link to="/stats" class="nav-link" active-class="active">Statistics</router-link>
    </nav>
    <router-view :persons="persons" @person-added="addPerson" @update-person="updatePerson" @delete-person="deletePerson"
     />

  </div>
</template>

<script>
import { ref } from 'vue';


export default {
  setup() {
    const persons = ref([
      {
        name: 'Hassan',
        yob: 1993,
        age: new Date().getFullYear() - 1993,
        weight: 68,
        height: 175,
        bmi: (68 / ((175 / 100) ** 2)).toFixed(2),
        photoUrl: 'https://randomuser.me/api/portraits/men/44.jpg',
      },
      {
        name: 'Siti',
        yob: 1998,
        age: new Date().getFullYear() - 1998,
        weight: 52,
        height: 160,
        bmi: (52 / ((160 / 100) ** 2)).toFixed(2),
        photoUrl: 'https://randomuser.me/api/portraits/women/3.jpg',
      },
    ]);

    function addPerson(newPerson) {
      persons.value.push(newPerson);
    }

    function updatePerson({ index, person }) {
      persons.value[index] = person;
    }

    function deletePerson(index) {
      persons.value.splice(index, 1);
    }

    return {
      persons,
      addPerson,
      updatePerson,
      deletePerson,
    };
  }
};
</script>

<style scoped>
.container {
  max-width: 700px;
  margin: 20px auto;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
}

h1 {
  font-weight: 700;
  margin-bottom: 10px;
}

.main-nav {
  margin-bottom: 20px;
  border-bottom: 1px solid #42b983;
  padding-bottom: 6px;
  font-weight: 600;
}

.main-nav a {
  color: #42b983;
  text-decoration: none;
  margin: 0 5px;
  padding: 3px 8px;
  border-radius: 5px;
  cursor: pointer;
}

.main-nav a.active,
.main-nav a:hover {
  background-color: #d7f0e6;
  color: #237a4f;
}
</style>
