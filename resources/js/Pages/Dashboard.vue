<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ToDoList from '@/Components/TodoList.vue'; // Ensure the filename matches exactly, including case sensitivity
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <div class="py-4">
            <div class="mx-auto max-w-5xl sm:px-6 md:px-12">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>
                </div>
                <!-- Include the ToDoList component -->
                <div class="mt-6">
                    <ToDoList />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
