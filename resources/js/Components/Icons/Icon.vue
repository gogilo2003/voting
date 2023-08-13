<script setup lang="ts">
import Dashboard from "./Dashboard.vue"
import Settings from "./Settings.vue"
import ChevronCircleForward from "./ChevronCircleForward.vue"
import ChevronCircleBack from "./ChevronCircleBack.vue"
import People from "./People.vue"
import Menu from "./Menu.vue"
import Certificate from "./Certificate.vue"
import Money from "./Money.vue"
import Event from "./Event.vue"
import Add from "./Add.vue"
import Edit from "./Edit.vue"
import Delete from "./Delete.vue"
import Key from "./Key.vue"
import Image from "./Image.vue"
import BarChart from "./BarChart.vue"
import StatsChart from "./StatsChart.vue"
import PieChart from "./PieChart.vue"

defineProps({
    type: String
})
</script>
<template>
    <Dashboard v-bind="$attrs" v-if="type == 'dashboard'" />
    <Settings v-bind="$attrs" v-if="type == 'settings'" />
    <ChevronCircleForward v-bind="$attrs" v-if="type == 'chevron-circle-forward'" />
    <ChevronCircleBack v-bind="$attrs" v-if="type == 'chevron-circle-back'" />
    <People v-bind="$attrs" v-if="type == 'people'" />
    <Money v-bind="$attrs" v-if="type == 'money'" />
    <Menu v-bind="$attrs" v-if="type == 'menu'" />
    <Certificate v-bind="$attrs" v-if="type == 'certificate'" />
    <Event v-bind="$attrs" v-if="type == 'event'" />
    <Add v-bind="$attrs" v-if="type == 'add'" />
    <Edit v-bind="$attrs" v-if="type == 'edit'" />
    <Delete v-bind="$attrs" v-if="type == 'delete'" />
    <Key v-bind="$attrs" v-if="type == 'key'" />
    <Image v-bind="$attrs" v-if="type == 'image'" />
    <BarChart v-bind="$attrs" v-if="type == 'bar-chart'" />
    <StatsChart v-bind="$attrs" v-if="type == 'stats-chart'" />
    <PieChart v-bind="$attrs" v-if="type == 'pie-chart'" />
</template>
