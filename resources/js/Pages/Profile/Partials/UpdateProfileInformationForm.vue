<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watchEffect } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '../../../Components/ActionMessage.vue';
import FormSection from '../../../Components/FormSection.vue';
import InputError from '../../../Components/InputError.vue';
import InputLabel from '../../../Components/InputLabel.vue';
import PrimaryButton from '../../../Components/PrimaryButton.vue';
import SecondaryButton from '../../../Components/SecondaryButton.vue';
import TextInput from '../../../Components/TextInput.vue';
import Modal from '../../../Components/Modal.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    phone: props.user.phone,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const video = ref(null);
const mediaStream = ref(null);
const showTakePhoto = ref(false)

const updateProfileInformation = () => {

    if (photoInput.value.files.length) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};

const takePhoto = () => {
    showTakePhoto.value = true
}

watchEffect(() => {
    video.value
    if (video.value) {
        navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
            mediaStream.value = stream
            video.value.srcObject = mediaStream.value
        })
    }
})

const beforeUnmount = () => {
    if (mediaStream.value) {
        mediaStream.value.getTracks().forEach((track) => track.stop());
    }
};

const dataURItoBlob = (dataURI) => {

    if (!dataURI || typeof dataURI !== 'string') {
        throw new Error('Invalid dataURI. Expected a non-empty string.');
    }

    const arr = dataURI.split(',');
    if (arr.length !== 2) {
        throw new Error('Invalid dataURI format. Expected data URI to be in the form "data:<MIME-type>;base64,<base64-data>".');
    }

    const byteString = atob(arr[1]);
    const mimeString = arr[0].split(':')[1].split(';')[0];
    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);
    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], { type: mimeString });
};

const capture = () => {
    const canvas = document.createElement('canvas');
    canvas.width = video.value.videoWidth;
    canvas.height = video.value.videoHeight;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video.value, 0, 0, canvas.width, canvas.height);

    photoPreview.value = canvas.toDataURL('image/jpeg');

    form.photo = dataURItoBlob(photoPreview.value)

    showTakePhoto.value = false
    beforeUnmount()

};
onBeforeUnmount(beforeUnmount);
</script>

<template>
    <Modal :show="showTakePhoto">
        <div class="shadow bg-gray-50 p-6">
            <h3 class="text-lg font-semibold">Take Photo</h3>
            <div>
                <video ref="video" width="640" height="480" autoplay></video>
                <SecondaryButton class="mt-6" @click="capture">Capture Photo</SecondaryButton>
                <!-- <img v-if="photoData" :src="photoData" alt="Captured Photo" /> -->
            </div>
        </div>
    </Modal>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

                <InputLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <SecondaryButton class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Photo
                </SecondaryButton>

                <SecondaryButton class="mt-2 mr-2" type="button" @click.prevent="takePhoto">
                    Take Photo
                </SecondaryButton>

                <SecondaryButton v-if="user.profile_photo_path" type="button" class="mt-2" @click.prevent="deletePhoto">
                    Remove Photo
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                    autocomplete="name" />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <!-- Phone -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="phone" value="Phone" />
                <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" required
                    autocomplete="phone" />
                <InputError :message="form.errors.phone" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" autocomplete="username" />
                <InputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Your email address is unverified.

                        <Link :href="route('verification.send')" method="post" as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @click.prevent="sendEmailVerification">
                        Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
