<template>
  <ckeditor
      v-if="editor"
      v-model="data"
      :editor="editor"
      :config="config" tag-name="textarea"
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

      const {
        Essentials,
        Autoformat,
        TextTransformation,
        Paragraph,
        Heading,
        Alignment,
        Bold,
        Italic,
        Underline,
        Strikethrough,
        Subscript,
        Superscript,
        BlockQuote,
        Code,
        Link, AutoLink,
        Image,
        ImageToolbar, ImageInsert,
        ImageCaption,
        ImageResize,
        ImageStyle,
        LinkImage,
        MediaEmbed,
        Table,
        TableToolbar,
        List, TodoList,
        Indent,
        IndentBlock, RemoveFormat
      } = cloud.data.value.CKEditor;

      return {
        // development license key
        // licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3ODYxNDcxOTksImp0aSI6ImRhNjZkNWM1LTEwMzUtNDVjZi05OWIzLWI4NTgxOWUxZWY1NCIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIiwiRTJQIiwiRTJXIl0sInZjIjoiM2IwZTA2YjQifQ.s7BLsta_ZMcpBQl6AFfkb1oeCOJV5rn72D7rgnk3CX-5Iu3GhbXe4HkSJBjXGQ1MP7xUl-XdY0biw2jfyADs-A',
        licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3ODYxNDcxOTksImp0aSI6IjdmZDhiMzY1LTQyOTctNDk3Ni04ZDY5LTlkNjQ0MmY2ODQ4NiIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCIsIkUyUCIsIkUyVyJdLCJ2YyI6ImYzNjZkOGY5In0.gABitp5nNJmtIswjRSZ6LgqyNUauceTTJHDzVLKKfECSfFtwueWrLwrgSJlsHmHx0GFK8SBLn8hXTHLAF_67XA',
        plugins: [Essentials, Autoformat, TextTransformation, Paragraph,
          Heading, Alignment, Bold, Italic,
          Underline, Strikethrough, Subscript, Superscript, BlockQuote, Code, Link, AutoLink,
          Image, ImageInsert, ImageToolbar, ImageCaption, ImageStyle, ImageResize, LinkImage,
          MediaEmbed, Table, TableToolbar, List, TodoList, Indent,
          IndentBlock, RemoveFormat],
        toolbar: ['undo', 'redo', '|', 'heading', '|', 'alignment', '|', 'bold',
          'italic',
          'underline', 'strikethrough', 'subscript', 'superscript', 'blockQuote', 'code', '|',
          'link', 'insertImage', 'mediaEmbed', '|', 'insertTable', '|', 'bulletedList',
          'numberedList', 'todoList', 'indent',
          'outdent', '|', 'removeFormat'],
        image: {
          toolbar: [
            'imageStyle:block',
            'imageStyle:side',
            '|',
            'toggleImageCaption',
            'imageTextAlternative',
            '|',
            'linkImage'
          ]
        },
        table: {
          contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
          defaultHeadings: {rows: 1, columns: 1}
        },
      };
    }
);
</script>
