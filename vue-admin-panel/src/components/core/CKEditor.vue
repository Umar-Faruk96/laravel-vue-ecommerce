<template>
  <ckeditor
      v-if="editor"
      v-model="data"
      :editor="editor"
      :config="config"
  />
</template>

<script setup>
import {ref, computed} from 'vue';
import {Ckeditor, useCKEditorCloud} from '@ckeditor/ckeditor5-vue';

const cloud = useCKEditorCloud({
  version: '46.0.0',
});

const data = ref('<p>Hello world!</p>');

const editor = computed(() => {
  if (!cloud.data.value) {
    return null;
  }

  return cloud.data.value.CKEditor.ClassicEditor;
});

const config = computed(() => {
  if (!cloud.data.value) {
    return null;
  }

  const {Essentials, Paragraph, Bold, Italic} = cloud.data.value.CKEditor;

  return {
    licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3ODYxNDcxOTksImp0aSI6ImRhNjZkNWM1LTEwMzUtNDVjZi05OWIzLWI4NTgxOWUxZWY1NCIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIiwiRTJQIiwiRTJXIl0sInZjIjoiM2IwZTA2YjQifQ.s7BLsta_ZMcpBQl6AFfkb1oeCOJV5rn72D7rgnk3CX-5Iu3GhbXe4HkSJBjXGQ1MP7xUl-XdY0biw2jfyADs-A',
    plugins: [Essentials, Paragraph, Bold, Italic],
    toolbar: ['undo', 'redo', '|', 'bold', 'italic', '|', 'formatPainter']
  };
});
</script>
