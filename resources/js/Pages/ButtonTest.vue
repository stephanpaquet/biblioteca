<script setup>
import Layout from '../Layouts/Layout.vue';
import ButtonDocs from '../Components/ButtonDocs.vue';

const props = defineProps({
  auth: {
    type: Object,
    default: null,
  },
});
</script>

<template>
  <Layout>
    <div class="min-h-screen bg-gray-50 py-8">
      <ButtonDocs />
    </div>
  </Layout>
</template>
