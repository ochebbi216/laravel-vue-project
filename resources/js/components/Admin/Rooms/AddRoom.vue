<template>
        <div class="main-wrapper">

    <div class="page-wrapper" style="padding-left: 20%;">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Add Room</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 ">
                    <form @submit.prevent="addRoom">
                        <div class="row formtype">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Number</label>
                                    <input class="form-control" type="text" id="room_number" v-model="room.room_number">
                                </div>
                                <div class="form-group">
                                    <label>Room Type</label>
                                    <select class="form-control" id="room_type" v-model="room.room_type">
                                        <option value="Single">Single</option>
                                        <option value="Double">Double</option>
                                        <option value="Triple">Triple</option>
                                        <option value="Suite">Suite</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Description (optional)</label>
                                    <textarea class="form-control" rows="5" id="description" v-model="room.description"></textarea>
                                </div>
                                <div class="form-group">
                                    <file-pond name="test" ref="pond" class-name="my-pond" label-idle="Drop files here..."
                                        allow-multiple="false" accepted-file-types="image/jpeg, image/png"
                                        v-bind:files="myFiles" v-on:init="handleFilePondInit" :server="serverOptions()" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group" >
                            <button type="submit" class="btn btn-primary veiwbutton">
                                <i class="fa-solid fa-floppy-disk"></i> Save

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        </div>
</template>
  
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import { useRouter } from 'vue-router';

// Create FilePond component
const FilePond = vueFilePond(FilePondPluginImagePreview);
const myFiles = ref([]);
const room = ref({
    room_number: 0,
    room_type: "",
    description: "",
    image: ""
});

// Router instance
const router = useRouter();

// Methods and logic for adding a room
const addRoom = async () => {
    try {
        await axios.post('http://localhost:8000/api/rooms/', room.value);
        router.push({ name: 'rooms' });
    } catch (error) {
        console.error(error);
    }
};


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

  
<style scoped></style>