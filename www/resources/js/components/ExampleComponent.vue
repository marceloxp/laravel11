<template>
    <div>
        <h2>Exemplo Vue Component</h2>
        <p>{{ message }}</p>
    </div>
</template>

<script setup>
defineProps({
    message: { type: String, default: "Olá do Vue!" },
});
</script>

<style scoped>
h2 {
    color: #42b983;
}
</style>
