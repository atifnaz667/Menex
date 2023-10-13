
<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div id="editorContainer" class="mt-4"></div>
            <div class="row ">
                <div class="col-sm-12 d-flex justify-content-center">
                    <button class="btn btn-info m-3 px-5" @click="onSave">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EditorJS from "@editorjs/editorjs";
import Table from '@editorjs/table';
import Header from '@editorjs/header';
import LinkTool from '@editorjs/link';
import Quote from '@editorjs/quote';
import ImageTool from '@editorjs/image';
import Embed from '@editorjs/embed';
import Delimiter from '@editorjs/delimiter';
import TextVariantTune from '@editorjs/text-variant-tune';

export default {
    data() {
        return {
        editor: null,
        };
    },
    mounted() {
        this.editor = new EditorJS({

            placeholder: 'Enter Title',
            holder: 'editorContainer',
            tools: {
                table: {
                    class: Table,
                    inlineToolbar: true,
                    config: {
                        rows: 2,
                        cols: 3,
                    },
                },
                header: {
                    class: Header,
                    config: {
                        levels: [2, 3, 4,5,6],
                        defaultLevel: 3
                    }
                },
                linkTool: {
                    class: LinkTool,
                    config: {
                        endpoint: 'http://localhost:8000/upload/blog/image', // Your backend endpoint for url data fetching,
                    }
                },
                quote: {
                    class: Quote,
                    inlineToolbar: true,
                    shortcut: 'CMD+SHIFT+O',
                    config: {
                        quotePlaceholder: 'Enter a quote',
                        captionPlaceholder: 'Quote\'s author',
                    },
                },
                image: {
                    class: ImageTool,
                    config: {
                        endpoints: {
                            byFile: 'http://localhost:8000/upload/blog/image', // Your backend file uploader endpoint
                            byUrl: 'http://localhost:8008/fetchUrl', // Your endpoint that provides uploading by Url
                        }
                    }
                },
                embed: {
                    class: Embed,
                    config: {
                        services: {
                        youtube: true,
                        coub: true,
                        codepen: {
                            regex: /https?:\/\/codepen.io\/([^\/\?\&]*)\/pen\/([^\/\?\&]*)/,
                            embedUrl: 'https://codepen.io/<%= remote_id %>?height=300&theme-id=0&default-tab=css,result&embed-version=2',
                            html: "<iframe height='300' scrolling='no' frameborder='no' allowtransparency='true' allowfullscreen='true' style='width: 100%;'></iframe>",
                            height: 300,
                            width: 600,
                            id: (groups) => groups.join('/embed/')
                        }
                        }
                    }
                },
                delimiter: Delimiter,
                textVariant: TextVariantTune,

            },
            defaultBlock: 'header'
        });
    },
    methods:{
        onSave(){
            this.editor.save().then((outputData) => {
                console.log('Article data: ', outputData)
            }).catch((error) => {
                console.log('Saving failed: ', error)
            });
        }
    }
};
</script>

<style>
.cdx-notifies {
    z-index: 2000;
}
</style>
