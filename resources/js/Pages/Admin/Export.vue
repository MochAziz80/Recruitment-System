<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import AdminSidebar from "@/Components/AdminSidebar.vue";
import { toast } from "vue3-toastify";

const types = [
    { label: "Users", value: "users" },
    { label: "Jobs", value: "jobs" },
    { label: "Applications", value: "applications" },
];

const columnsByType = {
    users: [
        { label: "ID", value: "id" },
        { label: "Name", value: "name" },
        { label: "Email", value: "email" },
        { label: "Role", value: "role" },
    ],
    jobs: [
        { label: "ID", value: "id" },
        { label: "Title", value: "title" },
        { label: "Description", value: "description" },
        { label: "Status", value: "status" },
    ],
    applications: [
        { label: "ID", value: "id" },
        { label: "User ID", value: "user_id" },
        { label: "Job ID", value: "job_id" },
        { label: "Status", value: "status" },
    ],
};

const form = useForm({
    types: [],
    columns: {},
});

const isPolling = ref(false);
const downloadUrl = ref("");
let pollingInterval = null;

watch(
    () => form.types,
    (newTypes) => {
        Object.keys(form.columns).forEach((type) => {
            if (!newTypes.includes(type)) delete form.columns[type];
        });
        newTypes.forEach((type) => {
            if (!form.columns[type]) form.columns[type] = [];
        });
    },
    { immediate: true }
);

function startPolling(filename) {
    isPolling.value = true;
    pollingInterval = setInterval(async () => {
        const res = await fetch(`/admin/export/status/${filename}`);
        const data = await res.json();
        if (data.ready) {
            clearInterval(pollingInterval);
            downloadUrl.value = data.download_url;
            isPolling.value = false;
            toast.success("File export sudah tersedia!");
        }
    }, 3000);
}

function submit() {
    if (form.types.length === 0) {
        toast.error("Pilih minimal satu tabel untuk diekspor.");
        return;
    }

    for (const type of form.types) {
        if (!form.columns[type] || form.columns[type].length === 0) {
            toast.error(`Pilih minimal satu kolom dari tabel ${type}.`);
            return;
        }
    }

    form.post("/admin/export", {
        onSuccess: (response) => {
            const filename = response.props?.filename ?? response?.filename;
            if (filename) {
                toast.info("Export dimulai, sedang diproses...");
                downloadUrl.value = "";
                startPolling(filename);
            } else {
                toast.error("Gagal mendapatkan nama file export.");
            }
        },
        onError: () => {
            toast.error("Export gagal.");
        },
    });
}
</script>

<template>
    <div class="flex min-h-screen bg-gray-50">
        <AdminSidebar />

        <main class="flex-1 p-6">
            <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow p-8 space-y-8 border">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-800">Export Data Excel</h1>
                    <p class="text-gray-600">Pilih tabel dan kolom yang ingin kamu ekspor.</p>
                </div>

                <!-- Pilih Tabel -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Pilih Tabel:</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label
                            v-for="option in types"
                            :key="option.value"
                            class="flex items-center space-x-2"
                        >
                            <input
                                type="checkbox"
                                :value="option.value"
                                v-model="form.types"
                                class="w-5 h-5 text-blue-600 rounded"
                            />
                            <span class="text-gray-800">{{ option.label }}</span>
                        </label>
                    </div>
                </div>

                <!-- Pilih Kolom -->
                <div v-for="type in form.types" :key="type" class="border-t pt-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">
                        Kolom dari <span class="capitalize">{{ type }}</span>:
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <label
                            v-for="col in columnsByType[type]"
                            :key="col.value"
                            class="flex items-center space-x-2"
                        >
                            <input
                                type="checkbox"
                                :value="col.value"
                                v-model="form.columns[type]"
                                class="w-5 h-5 text-green-600 rounded"
                            />
                            <span class="text-gray-700">{{ col.label }}</span>
                        </label>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-6 flex items-center gap-4">
                    <button
                        @click.prevent="submit"
                        :disabled="form.processing || isPolling"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded disabled:opacity-50"
                    >
                        Export Sekarang
                    </button>

                    <span v-if="isPolling" class="text-blue-600">Sedang memproses export...</span>
                </div>

                <!-- Tombol Download -->
                <div v-if="downloadUrl" class="pt-4">
                    <a
                        :href="downloadUrl"
                        target="_blank"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded font-semibold inline-block"
                    >
                        Unduh File Export
                    </a>
                </div>
            </div>
        </main>
    </div>
</template>
