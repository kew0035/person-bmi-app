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
    <router-view
      :persons="persons"
      @person-added="onPersonAdded"
      @update-person="onPersonUpdated"
      @delete-person="onPersonDeleted"
    />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  setup() {
    const persons = ref([]);

    // 从后端获取所有 persons
    function fetchPersons() {
      fetch('http://localhost:8085/person')
        .then(res => {
          if (!res.ok) throw new Error('Failed to fetch persons');
          return res.json();
        })
        .then(data => {
          persons.value = data;
        })
        .catch(err => {
          console.error(err);
          persons.value = [];
        });
    }

    onMounted(() => {
      fetchPersons();
    });

    // 新增后重新拉数据
    function onPersonAdded() {
      fetchPersons();
    }

    // 更新后重新拉数据
    function onPersonUpdated() {
      fetchPersons();
    }

    // 删除后重新拉数据
    function onPersonDeleted() {
      fetchPersons();
    }

    return {
      persons,
      onPersonAdded,
      onPersonUpdated,
      onPersonDeleted,
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
