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
            // Use production license key for both production and local environments
            licenseKey: (import.meta.env.VITE_APP_ENV === 'local' || !import.meta.env.DEV) ? import.meta.env.VITE_CKEDITOR_PROD_LICENSE : import.meta.env.VITE_CKEDITOR_DEV_LICENSE,
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
