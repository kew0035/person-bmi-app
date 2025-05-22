<template>
  <div class="container">
    <h1>Person BMI Web App</h1>

    <nav class="main-nav">
      <a href="#"
         :class="{active: currentView === 'add'}"
         @click.prevent="currentView = 'add'">Add Person</a> |
      <a href="#"
         :class="{active: currentView === 'list'}"
         @click.prevent="currentView = 'list'">View All</a> |
      <a href="#"
         :class="{active: currentView === 'view'}"
         @click.prevent="currentView = 'view'">View One</a> |
      <a href="#"
         :class="{active: currentView === 'edit'}"
         @click.prevent="currentView = 'edit'">Edit</a> |
      <a href="#"
         :class="{active: currentView === 'delete'}"
         @click.prevent="currentView = 'delete'">Delete</a> |
      <a href="#"
         :class="{active: currentView === 'stats'}"
         @click.prevent="currentView = 'stats'">Statistics</a>
    </nav>

    <AddPerson v-if="currentView === 'add'" @person-added="addPerson" />
    <ListPersons v-if="currentView === 'list'" :persons="persons" />
    <ViewPerson v-if="currentView === 'view'" :persons="persons" />
    <EditPerson v-if="currentView === 'edit'" :persons="persons" @update-person="updatePerson" />
    <DeletePerson v-if="currentView === 'delete'" :persons="persons" @delete-person="deletePerson" />
    <BmiStats v-if="currentView === 'stats'" :persons="persons" />

  </div>
</template>

<script>
import { ref } from 'vue';
import AddPerson from './components/AddPerson.vue';
import ListPersons from './components/ListPersons.vue';
import ViewPerson from './components/ViewPerson.vue';
import EditPerson from './components/EditPerson.vue';
import DeletePerson from './components/DeletePerson.vue';
import BmiStats from './components/BmiStats.vue';

export default {
  components: {
    AddPerson,
    ListPersons,
    ViewPerson,
    EditPerson,
    DeletePerson,
    BmiStats
  },
  setup() {
    const currentView = ref('add');

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
      currentView,
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
