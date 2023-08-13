<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue';
import InputError from '../../Components/InputError.vue'
import TextInput from '../../Components/TextInput.vue'
import InputLabel from '../../Components/InputLabel.vue'
import PrimaryButton from '../../Components/PrimaryButton.vue'
import SecondaryButton from '../../Components/SecondaryButton.vue'
import { useForm, Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { cElection, cNotification } from '../../types'
import { ref, watch } from "vue";
import VueTailwindDatepicker from 'vue-tailwind-datepicker'

const props = defineProps({ elections: Array<cElection>, notification: Object, positions: Array })

const form = useForm({
    id: null,
    title: "",
    description: "",
    positions: [],
    start: null,
    end: null,
})

const edit = ref(false)

const editElection = (election: cElection) => {

    edit.value = true
    form.id = election.id
    form.title = election.title
    form.description = election.description
    form.start = new Date(Date.parse(election.start))
    form.end = new Date(Date.parse(election.end))
    form.positions = election?.positions?.map(item => item.id)
}

const cancelEdit = () => {
    edit.value = false
    form.id = null
    form.title = ""
    form.description = ""
    form.start = null
    form.end = null
    form.positions = []
}
const submit = () => {
    if (!form.start && !form.end) {
        form.start = dateValue.value.startDate
        form.end = dateValue.value.endDate
    }

    if (edit.value) {
        form.patch(route('dashboard-elections-update', form.id), {
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
        form.post(route('dashboard-elections'), {
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

const dateValue = ref({
    startDate: null,
    endDate: null,
})

const updateDateValue = () => {
    {
        dateValue.value = "2023-07-04 07:39:33 ~ 2023-08-22 07:39:33"//form.start + " ~ " + form.end
    }
}
watch(() => form.start, (value) => updateDateValue())

watch(() => form.end, (value) => updateDateValue())

const formatDate = (d: Date) => {
    // 2023-07-04 07:39:33 ~ 2023-08-22 07:39:33
    return d.getFullYear()
        + "-" + d.getMonth().toString().padStart(2, '0')
        + "-" + d.getDate().toString().padStart(2, '0')
        + " " + d.getHours().toString().padStart(2, '0')
        + ":" + d.getMinutes().toString().padStart(2, '0')
        + ":" + d.getSeconds().toString().padStart(2, '0')
}

</script>

<template>
    <AppLayout title="Elections">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Elections
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white shadow-xl sm:rounded-lg p-4">
                        <form @submit.prevent="submit">
                            <h3 class="text-lg font-semibold">Create/Edit Election</h3>
                            <hr class="my-3">
                            <!-- title
                            description
                            start_at
                            end_at -->
                            <div class="mb-6">
                                <InputLabel for="title" value="Title" />
                                <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required
                                    autofocus autocomplete="title" />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>
                            <div class="mb-6">
                                <InputLabel for="description" value="Description" />
                                <TextInput id="description" v-model="form.description" type="text" class="mt-1 block w-full"
                                    required autofocus autocomplete="description" />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                            <div class="mb-6">
                                <InputLabel for="start_end" value="Start to End" />
                                <vue-tailwind-datepicker v-model="dateValue" />
                                <InputError class="mt-2" :message="form.errors.end" />
                                <InputError class="mt-2" :message="form.errors.start" />
                            </div>
                            <div class="mb-6">
                                <div v-for="position in positions">
                                    <label class="relative inline-flex items-center mb-4 cursor-pointer">
                                        <input type="checkbox" :value="position.id" class="sr-only peer"
                                            v-model="form.positions">
                                        <div
                                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                            v-text="position.name"></span>
                                    </label>
                                </div>
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
                        <h3 class="text-lg font-semibold">Elections</h3>
                        <hr class="my-4">
                        <div class="spacing-y-3">
                            <div v-for="(election, index) in elections"
                                class="border border-stone-200 px-3 py-1 my-2 hover:shadow-lg shadow rounded-md cursor-pointer flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div v-text="index + 1"
                                        class="w-8 h-8 rounded-full bg-red-50 flex-none flex items-center justify-center">
                                    </div>
                                    <div class="flex-1">
                                        <span v-text="election.title" class="text-md font-semibold block"></span>
                                        <span v-text="election.description"
                                            class="whitespace-nowrap text-sm text-stone-600"></span>
                                        <div class="flex text-xs font-semibold text-stone-700">
                                            <div v-html="election.start" class="whitespace-nowrap"></div>&nbsp;-&nbsp;
                                            <div v-html="election.end" class="whitespace-nowrap"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <Link :href="route('dashboard-elections-show', election.id)"
                                        class="font-semibold text-sm text-blue-700 hover:text-blue-500 transition-colors duration-300">
                                    View
                                    </Link>
                                    <button
                                        class="font-semibold text-sm text-green-700 hover:text-green-500 transition-colors duration-300"
                                        @click="editElection(election)">Edit</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
