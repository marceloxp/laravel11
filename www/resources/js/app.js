import { createApp } from 'vue';
import './bootstrap';  // mantenha seu axios

// Componentes reutilizáveis (adicione os seus aqui)
import ExampleComponent from './components/ExampleComponent.vue';
// ... adicione mais conforme precisar (ex: FormLogin, etc.)

const app = createApp({});

// Registre componentes globais
app.component('example-component', ExampleComponent);
// ... registre outros

// Globals (como no exemplo + seus customs)
app.config.globalProperties.$datasite = window.datasite;   // seu datasite

app.mount('#app');
