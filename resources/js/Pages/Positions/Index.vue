<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue';
import InputError from '../../Components/InputError.vue'
import TextInput from '../../Components/TextInput.vue'
import InputLabel from '../../Components/InputLabel.vue'
import PrimaryButton from '../../Components/PrimaryButton.vue'
import SecondaryButton from '../../Components/SecondaryButton.vue'
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { cPosition } from '../../types'
import { ref } from "vue";
import { Link } from '@inertiajs/vue3'

const props = defineProps({ positions: Array<cPosition>, notification: Object })

const form = useForm({
    id: null,
    name: "",
    description: "",
})

const edit = ref(false)

const editElection = (position: cPosition) => {

    edit.value = true
    form.id = position.id
    form.name = position.name
    form.description = position.description
}

const cancelEdit = () => {
    edit.value = false
    form.id = null
    form.name = ""
    form.description = ""
}

const submit = () => {
    if (edit.value) {
        form.patch(route('dashboard-positions-update', form.id), {
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Voting Notification',
                    text: props.notification.success
                })
                if (props.notification.success) {
                    form.reset()
                    edit.value = false
                }
            }
        })
    }
    else {
        form.post(route('dashboard-positions'), {
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Voting Notification',
                    text: props.notification.success
                })
                if (props.notification.success) {
                    form.reset()
                }
            }
        })
    }
}

</script>

<template>
    <AppLayout title="Positions">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Positions
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white shadow-xl sm:rounded-lg p-4">
                        <form @submit.prevent="submit">
                            <h3 class="text-lg font-semibold">Create/Edit Position</h3>
                            <hr class="my-3">
                            <div class="mb-6">
                                <InputLabel for="name" value="Name" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                                    autofocus autocomplete="name" />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                            <div class="mb-6">
                                <InputLabel for="description" value="Description" />
                                <TextInput id="description" v-model="form.description" type="text" class="mt-1 block w-full"
                                    required autofocus autocomplete="description" />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                            <div class="flex gap-2 justify-between">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Submit
                                </PrimaryButton>
                                <SecondaryButton v-if="edit" @click="cancelEdit">
                                    Cancel
                                </SecondaryButton>
                            </div>
                        </form>
                    </div>
                    <div class="bg-white shadow-xl sm:rounded-lg p-4">
                        <h3>Elections</h3>
                        <div class="spacing-y-3">
                            <div v-for="(position, index) in positions"
                                class="border border-stone-200 px-3 py-1 my-2 hover:shadow-lg shadow rounded-md cursor-pointer flex items-center gap-3">
                                <div v-text="index + 1"
                                    class="w-8 h-8 rounded-full bg-red-50 flex-none flex items-center justify-center">
                                </div>
                                <div class="flex-1">
                                    <span v-text="position.name" class="text-md font-semibold block"></span>
                                    <span v-text="position.description"
                                        class="whitespace-nowrap text-sm text-stone-600"></span>
                                </div>
                                <div class="flex gap-2">
                                    <button
                                        class="font-semibold text-sm text-green-700 hover:text-green-500 transition-colors duration-300"
                                        @click="editElection(position)">Edit</button>
                                    <Link method="delete" as="button"
                                        class="font-semibold text-sm text-red-700 hover:text-red-500 transition-colors duration-300"
                                        :href="route('dashboard-positions-delete', position.id)">Delete</Link>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
