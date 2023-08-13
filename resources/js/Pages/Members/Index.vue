<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue';
import InputError from '../../Components/InputError.vue'
import TextInput from '../../Components/TextInput.vue'
import InputLabel from '../../Components/InputLabel.vue'
import PrimaryButton from '../../Components/PrimaryButton.vue'
import SecondaryButton from '../../Components/SecondaryButton.vue'
import { useForm, Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { onMounted, ref, watch } from 'vue'
import { debounce } from "lodash"
import Modal from '../../Components/Modal.vue';
import Icon from '../../Components/Icons/Icon.vue'

const props = defineProps({ members: Object, notification: Object, retSearch: String })

const form = useForm({
    id: null,
    name: "",
    email: "",
    phone: "",
})

const formUpload = useForm({
    file: null
})

const edit = ref(false)

const cancelEdit = () => {
    form.errors.email = ""
    form.errors.phone = ""
    form.errors.name = ""
    form.reset()
    edit.value = false
}

const editMember = (member) => {
    form.id = member.id
    form.name = member.name
    form.phone = member.phone
    form.email = member.email

    edit.value = true
}

const createMember = (member) => {
    form.id = null
    form.name = ""
    form.phone = ""
    form.email = ""

    edit.value = false
}

const search = ref()

const submit = () => {
    if (edit.value) {
        form.patch(route('dashboard-members-update', form.id), {
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    text: props?.notification?.success
                })
            },
            onError: (error) => {
                Swal.fire({
                    icon: 'error',
                    text: "An error occurred"
                })
            }
        })
    } else {
        form.post(route('dashboard-members'), {
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    text: props?.notification?.success
                })
            },
            onError: (error) => {
                Swal.fire({
                    icon: 'error',
                    text: "An error occurred"
                })
            }
        })
    }

}

const upload = () => {
    formUpload.post(route('dashboard-members-upload'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                text: props?.notification?.success
            })
        },
        onError: (error) => {
            console.log("Error:", error);
            Swal.fire({
                icon: 'error',
                text: "An error occurred"
            })
        }
    })
}

watch(() => search.value, debounce((value) => {

    router.get(route('dashboard-members'), { search: search.value }, {
        preserveScroll: true,
        preserveState: true,
        only: ['members']
    })


}, 500))

onMounted(() => {
    search.value = props.retSearch
})

const photoDialog = ref(false)

const photoForm = useForm({
    id: null,
    photo: null
})


const editMemberPhoto = (member) => {
    selectedMember.value = member
    photoForm.id = member.id
    photoDialog.value = true
}

const submitPhoto = () => {
    photoForm.post(route('dashboard-members-photo'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                text: props.notification.success
            })
            photoDialog.value = false
            photoForm.reset()
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                text: "An error ocurred"
            })
        }
    })
}

const selectedMember = ref(Object);

const cancelPhotoUpload = () => {
    photoForm.reset();
    photoDialog.value = false
}
</script>

<template>
    <Modal :show="photoDialog" closeable>
        <form @submit.prevent="submitPhoto" class="shadow bg-white p-6 space-y-4">
            <h4 class="text-xl font-semibold" v-text="`Upload photo for ${selectedMember?.name}`"></h4>
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to
                                upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden"
                        @input="photoForm.photo = $event.target.files[0]" />
                </label>
            </div>
            <div>
                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{ form.progress.percentage }}%
                </progress>
            </div>
            <div class="flex items-center justify-between">
                <SecondaryButton @click="cancelPhotoUpload">Cancel</SecondaryButton>
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Upload
                </PrimaryButton>
            </div>
        </form>
    </Modal>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-6">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                            <form @submit.prevent="submit">
                                <h3 class="text-lg font-semibold">Create/Edit Election</h3>
                                <hr class="my-3">
                                <div class="mb-6">
                                    <InputLabel for="name" value="Name" />
                                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                                        autofocus autocomplete="name" />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>
                                <div class="mb-6">
                                    <InputLabel for="phone" value="Phone" />
                                    <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full"
                                        required autofocus autocomplete="phone" />
                                    <InputError class="mt-2" :message="form.errors.phone" />
                                </div>
                                <div class="mb-6">
                                    <InputLabel for="email" value="Email" />
                                    <TextInput id="email" v-model="form.email" type="text" class="mt-1 block w-full"
                                        autofocus autocomplete="email" />
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>
                                <div class="flex justify-between">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Submit
                                    </PrimaryButton>
                                    <SecondaryButton type="button" @click="cancelEdit"
                                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Cancel
                                    </SecondaryButton>
                                </div>
                            </form>
                        </div>
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                            <form @submit.prevent="upload">
                                <h3 class="text-lg font-semibold">Upload Members</h3>
                                <hr class="my-3">

                                <div class="mb-6 flex flex-col gap-2">

                                    <label class="block text-sm font-medium text-gray-900 dark:text-white"
                                        for="members_upload">Upload file</label>

                                    <input id="members_upload" type="file" @input="formUpload.file = $event.target.files[0]"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="members_upload_help">
                                        Upload
                                        only xls, xlsx and csv files with the fields name, phone, email in any order</div>
                                    <progress class="w-full bg-gray-200 rounded-full h-0.5 mb-4 dark:bg-gray-700"
                                        v-if="formUpload.progress" :value="formUpload.progress.percentage" max="100">
                                        {{ formUpload.progress.percentage }}%
                                    </progress>
                                </div>

                                <div class="flex justify-between">
                                    <PrimaryButton :class="{ 'opacity-25': formUpload.processing }"
                                        :disabled="formUpload.processing">
                                        Upload
                                    </PrimaryButton>
                                    <SecondaryButton type="button" @click="cancelEdit"
                                        :class="{ 'opacity-25': formUpload.processing }" :disabled="formUpload.processing">
                                        Cancel
                                    </SecondaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <TextInput id="name" v-model="search" type="text" class="mt-1 block w-full" autofocus
                                    autocomplete="name" />
                            </div>
                            <div class="flex justify-end gap-2">
                                <Link as="button" method="patch" :href="route('dashboard-members-passwords')">Mass Password
                                Reset</Link>
                                <button @click="createMember">Create</button>
                                <a :href="route('dashboard-members-download')">Download</a>
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="w-full border-separate">
                            <div v-for="member in members.data"
                                class="border border-stone-200 px-3 py-2 my-2 hover:shadow-lg shadow rounded-md cursor-pointer flex items-center gap-3 justify-between">
                                <div class="flex gap-2">
                                    <div
                                        class="font-bold h-12 w-12 flex-none bg-gray-200 flex items-center justify-center rounded-full overflow-hidden">
                                        <img :src="member.profile_photo_path ? `/storage/${member.profile_photo_path}` : ''"
                                            alt="" class="h-full w-full object-cover rounded-full scale-105">
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg" v-text="member.name"></h3>
                                        <div class="flex flex-nowrap gap-2 text-sm text-stone-700 items-center">
                                            <span v-text="`Phone: ${member.phone}`"></span>
                                            <span class="border-1 border-r border-stone-400 h-2" v-if="member.email"></span>
                                            <span v-text="`Email: ${member.email}`" v-if="member.email"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button
                                        class="font-semibold text-sm text-green-700 hover:text-green-500 transition-colors duration-300"
                                        @click="editMemberPhoto(member)">
                                        <Icon type="image" class="h-5 w-5" />
                                    </button>
                                    <button
                                        class="font-semibold text-sm text-green-700 hover:text-green-500 transition-colors duration-300"
                                        @click="editMember(member)">
                                        <Icon type="edit" class="h-5 w-5" />
                                    </button>
                                    <Link method="patch" as="button"
                                        class="font-semibold text-sm text-indigo-700 hover:text-indigo-500 transition-colors duration-300"
                                        :href="route('dashboard-members-password', member.id)">
                                    <Icon type="key" class="h-5 w-5" />
                                    </Link>
                                    <Link method="delete" as="button"
                                        class="font-semibold text-sm text-red-700 hover:text-red-500 transition-colors duration-300"
                                        :href="route('dashboard-members-delete', member.id)">
                                    <Icon type="delete" class="h-5 w-5" />
                                    </Link>
                                </div>
                            </div>
                            <div v-if="members.last_page > 1" class="flex gap-1 justify-center mt-6">
                                <component :href="link.url" :is="link.url && !link.active ? Link :'span'"
                                    v-for="link in members.links"
                                    class=" border p-2 flex first:w-auto last:w-auto w-6 h-6 rounded-full items-center justify-center"
                                    :class="{ 'bg-blue-600 text-white border-transparent': link.active, 'border-blue-600': !link.active, 'border-stone-700 text-stone-700': !link.url }"
                                    v-html="link.label">
                                </component>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
