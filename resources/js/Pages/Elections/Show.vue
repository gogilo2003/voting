<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue';
import InputError from '../../Components/InputError.vue'
import TextInput from '../../Components/TextInput.vue'
import InputLabel from '../../Components/InputLabel.vue'
import PrimaryButton from '../../Components/PrimaryButton.vue'
import SecondaryButton from '../../Components/SecondaryButton.vue'
import DialogModal from '../../Components/DialogModal.vue'
import { useForm, Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { cElection, cPosition } from '../../types'
import { onMounted, ref, watch } from "vue";
import VueTailwindDatepicker from 'vue-tailwind-datepicker'
import debounce from 'lodash/debounce'

const props = defineProps({ election: Object, notification: Object, members: Array })

const form = useForm({
    id: null,
    election: null,
    position: null,
    candidates: [],
})

const position = ref({});
const positions = ref(props.election?.positions);

const changePosition = (lPosition: cPosition) => {
    positions.value = positions.value.map((item: cPosition) => ({ ...item, active: lPosition.id == item.id }))
    position.value = lPosition
}

const filteredMembers = ref([])

onMounted(() => {
    changePosition(props.election?.positions[0])
    filteredMembers.value = props.members
})

const showDialog = ref(false)

const addCandidate = () => {
    showDialog.value = true
}

const close = () => {
    showDialog.value = false
}

const search = ref()

watch(() => search.value, debounce((value) => {
    filteredMembers.value = props.members.filter((item) => item?.name?.toLowerCase()?.includes(value))

}, 500))

const submit = () => {
    form.election = props.election?.id
    form.position = position.value.id
    form.post(route('dashboard-candidates-store'), {
        onFinish: () => {
            Swal.fire({
                icon: 'success',
                title: 'Voting Notification',
                text: props.notification.success
            })
            form.reset();
            showDialog.value = false
            positions.value = props.election.positions
        },
        preserveScroll: true,
        preserveState: true,
        only: ['election', 'notification']
    })
}
</script>

<template>
    <DialogModal :show="showDialog" :closeable="true" max-width="md">
        <template #title>
            <div class="flex items-center justify-between">
                <h3>Select Candidates for Position of {{ position.name }}</h3>
                <button @click="close">x</button>
            </div>
        </template>
        <template #content>
            <div class="flex flex-col h-full overflow-auto gap-3">
                <div>
                    <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="default-search"
                            class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Members..." v-model="search">
                        <!-- <button type="submit"
                            class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button> -->
                    </div>
                </div>

                <div class="border border-stone-200 p-3 max-h-96 overflow-y-auto rounded-lg">
                    <div v-for="member in filteredMembers">
                        <label class="relative flex items-center mb-4 cursor-pointer">
                            <input type="checkbox" :value="member.id" class="sr-only peer" v-model="form.candidates">
                            <div
                                class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0 after:translate-y-[calc(50%_+_4px)] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                            </div>
                            <div class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                <img :src="member.photo" alt="" />
                                <h3 v-text="member.name" class="text-lg font-semibold"></h3>
                                <p v-text="member.phone" class="opacity-40 text-xs"></p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <form @submit.prevent="submit">
                <PrimaryButton :disabled="form.processing" :class="{ 'opacity-30': form.processing }">Save</PrimaryButton>
            </form>
        </template>
    </DialogModal>
    <AppLayout :title="election.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" v-text="election.title"></h2>
            <h3 v-text="election.description"></h3>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-[18rem_auto] gap-3">
                    <div class="shadow border-stone-200">
                        <div class="p-8">
                            <div v-html="`<span class='font-semibold uppercase'>Start: </span>${election.start}`"></div>
                            <div v-html="`<span class='font-semibold uppercase'>End: </span>${election.end}`"></div>
                            <hr class="my-4">
                            <h3 class="text-lg uppercase font-semibold">Vacancies</h3>
                            <hr class="my-4">
                            <div :class="{ 'bg-stone-400 text-stone-50': position.active }"
                                class="bg-stone-200 py-3 px-5 hover:bg-stone-400 cursor-pointer transition-color duration-300 hover:text-stone-50 my-1"
                                v-for="position in positions" v-text="position.name" @click="changePosition(position)">
                            </div>
                        </div>
                    </div>
                    <div class="shadow border-stone-200 p-8">
                        <template v-if="position">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold uppercase" v-text="position?.name"></h3>
                                    <h4 class="text-sm font-semibold text-stone-600" v-text="position?.description"></h4>
                                </div>
                                <div>
                                    <button @click="addCandidate">Add</button>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="">
                                <div class="p-3 rounded-xl shadow border border-stone-200 mb-3 flex items-center justify-between gap-3"
                                    v-for="candidate in position.candidates">
                                    <div class="flex gap-3">
                                        <img :src="candidate.photo" alt="" class="h-12 w-12 rounded-full">
                                        <div>
                                            <h3 v-text="candidate.name" class="text-lg font-semibold uppercase"></h3>
                                            <p v-text="candidate.phone" class="text-xs font-semibold text-gray-700"></p>
                                        </div>
                                    </div>
                                    <div>
                                        <Link as="button" method="delete"
                                            :href="route('dashboard-candidates-delete', candidate.id)">Delete
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
