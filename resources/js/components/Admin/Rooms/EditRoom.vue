<template>
        <Header></Header>
<div class="main-wrapper">
    <div class="page-wrapper bg" style="padding-left: 20%;">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Edit Room: {{ room.room_number }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 align-content-center">
                <form @submit.prevent="updateRoom">
                    <div class="form-group">
                        <label>Room Number</label>
                        <input type="text" class="form-control" v-model="room.room_number" >
                    </div>
                    <div class="form-group">
                        <label>Room Type</label>
                        <select class="form-control" v-model="room.room_type">
                            <option value="Single">Single</option>
                            <option value="Double">Double</option>
                            <option value="Triple">Triple</option>
                            <option value="Suite">Suite</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" v-model="room.description"></textarea>
                    </div>
                    <div class="form-group">
                        <file-pond name="test" ref="pond" class-name="my-pond" label-idle="Drop files here..."
                            allow-multiple="false" accepted-file-types="image/jpeg, image/png" v-bind:files="myFiles"
                            v-on:init="handleFilePondInit" :server="serverOptions()" />
                    </div>
                    <div class="form-group" >
                    <button type="submit" class="btn btn-primary veiwbutton">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
        </div>
</template>
  
<script setup>
import axios from 'axios';
import { ref, onMounted } from "vue"
import { useRouter, useRoute } from 'vue-router';
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import Header from "../layouts/Header.vue";

const FilePond = vueFilePond(FilePondPluginImagePreview);
const myFiles = ref([]);
const router = useRouter();
const route = useRoute();
const room = ref({});

const fetchRoom = async () => {
    try {
        const res = await axios.get(`http://localhost:8000/api/rooms/${route.params.id}`);
        room.value = res.data;
    } catch (err) {
        console.error(err);
    }
};

const updateRoom = async () => {
    try {
        await axios.put(`http://localhost:8000/api/rooms/${route.params.id}`, room.value);
        router.push({ name: 'rooms' });
    } catch (err) {
        console.error('Error updating the room:', err);
    }
};
onMounted(() => {
    fetchRoom();
});
const handleFilePondInit = async () => {

    if (room.value.image) {

        myFiles.value = [
            {
                source: room.value.image,
                options: { type: 'local' }
            }
        ]
    }
}
const serverOptions = () => {
    console.log('server pond');
    return {
        load: (source, load, error, progress, abort, headers) => {
            var myRequest = new Request(source);
            fetch(myRequest).then(function (response) {
                response.blob().then(function (myBlob) {
                    load(myBlob);
                });
            });
        },
        process: (fieldName, file, metadata, load, error, progress, abort) => {
            const data = new FormData();
            data.append('file', file);
            data.append('upload_preset', 'GLID5IIT');
            data.append('cloud_name', 'esps');
            data.append('public_id', file.name);
            axios.post('https://api.cloudinary.com/v1_1/esps/upload', data)
                .then((response) => response.data)
                .then((data) => {
                    console.log(data);

                    room.value.image = data.url;
                    load(data);
                })
                .catch((error) => {
                    console.error('Error uploading file:', error);
                    error('Upload failed');
                    abort();
                });
        },
    };
};
</script>
<style scoped>
</style>