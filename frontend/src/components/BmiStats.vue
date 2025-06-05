<template>
  <section class="view-section">
    <h2>Statistics / Analysis</h2>
    <div v-if="totalPersons === 0">
      <p>No data to display.</p>
    </div>
    <div v-else>
      <div class="stat-item" v-for="(cat, key) in bmiCategories" :key="key">
        <div>
          <strong>{{ cat.label }}: </strong>
          {{ cat.count }} ({{ cat.percent.toFixed(1) }}%)
        </div>
        <div class="progress-bar-bg">
          <div class="progress-bar-fill" :style="{width: cat.percent + '%', backgroundColor: cat.color}"></div>
        </div>
      </div>

      <!-- Pie Chart Canvas -->
      <canvas ref="chartCanvas" width="400" height="300" class="chart-canvas"></canvas>
    </div>
  </section>
</template>

<script>
import { ref, onMounted, nextTick } from 'vue';
import Chart from 'chart.js/auto';

export default {
  setup() {
    const totalPersons = ref(0);
    const bmiCategories = ref({
      underweight: { label: 'Underweight', count: 0, percent: 0, color: '#2196f3' },
      normal: { label: 'Normal', count: 0, percent: 0, color: '#4caf50' },
      overweight: { label: 'Overweight', count: 0, percent: 0, color: '#ff9800' },
      obese: { label: 'Obese', count: 0, percent: 0, color: '#f44336' },
    });

    const chartCanvas = ref(null);
    let bmiChart = null;

    async function fetchPersons() {
      try {
        const response = await fetch('http://localhost:8085/person');  
        if (!response.ok) {
          throw new Error(`Failed to fetch persons: ${response.status}`);
        }
        const data = await response.json();
        calculateStats(data); // update statistic
        nextTick(() => renderChart());    // chart
      } catch (error) {
        console.error('Error fetching persons:', error);
      }
    }

    function calculateStats(persons) {
      totalPersons.value = persons.length;
      const counts = { underweight: 0, normal: 0, overweight: 0, obese: 0 };

      persons.forEach(p => {
        const bmi = parseFloat(p.bmi);
        if (bmi < 18.5) counts.underweight++;
        else if (bmi < 24.9) counts.normal++;
        else if (bmi < 29.9) counts.overweight++;
        else counts.obese++;
      });

      for (const key in bmiCategories.value) {
        bmiCategories.value[key].count = counts[key];
        bmiCategories.value[key].percent = totalPersons.value ? (counts[key] / totalPersons.value) * 100 : 0;
        console.log(`${key} category:`, bmiCategories.value[key]);
      }
    }

    function renderChart() {
      if (!chartCanvas.value) {
        console.error("Canvas element is null");
        return;
      }
      console.log("Canvas element:", chartCanvas.value);

      const ctx = chartCanvas.value.getContext('2d');
      console.log("Context:", ctx);

      if (bmiChart) {
        bmiChart.destroy();  // Destroy old chart
        console.log("Destroyed old chart");
      }

      bmiChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: Object.values(bmiCategories.value).map(cat => cat.label),
          datasets: [{
            data: Object.values(bmiCategories.value).map(cat => cat.count),
            backgroundColor: Object.values(bmiCategories.value).map(cat => cat.color),
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: 'bottom' }
          },
        }
      });
    }

    onMounted(() => {
      fetchPersons();
    });

    return { totalPersons, bmiCategories, chartCanvas };
  }
};
</script>

<style scoped>
.stat-item {
  margin-bottom: 12px;
}

.progress-bar-bg {
  background-color: #eee;
  border-radius: 6px;
  height: 16px;
  width: 100%;
  margin-top: 4px;
  overflow: hidden;
}

.progress-bar-fill {
  height: 100%;
  border-radius: 6px 0 0 6px;
}

.chart-canvas {
  margin-top: 20px;
  max-width: 600px;
  max-height: 400px;
}
</style>
