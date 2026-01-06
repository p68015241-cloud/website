<x-app-layout>
    <div
        x-data="{
            open: false,
            videoSrc: '',

            totalChickens: {{ $totalChickens }},
            abnormalChickens: {{ $abnormalChickens }},
            alerts: @js($alerts),

            filter: 'all', // all | abnormal

            fetchData() {
                fetch('/api/dashboard-data')
                    .then(res => res.json())
                    .then(data => {
                        this.totalChickens = data.totalChickens;
                        this.abnormalChickens = data.abnormalChickens;
                        this.alerts = data.alerts;
                    });
            },

            filteredAlerts() {
                if (this.filter === 'abnormal') {
                    return this.alerts.filter(a =>
                        a.behavior.includes('Abnormal')
                    );
                }
                return this.alerts;
            },

            init() {
                this.fetchData();
                setInterval(() => this.fetchData(), 5000);
            }
        }"
        x-init="init()"
        class="p-6 space-y-6"
    >

        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded shadow text-center">
                <div class="text-4xl font-bold" x-text="totalChickens"></div>
                <div class="text-gray-500">Total Chickens Detected</div>
            </div>

            <div class="bg-white p-6 rounded shadow text-center">
                <div class="text-4xl font-bold text-red-600" x-text="abnormalChickens"></div>
                <div class="text-gray-500">Abnormal Chickens</div>
            </div>
        </div>

        <div class="flex gap-2">
            <button
                class="px-4 py-1 rounded border"
                :class="filter === 'all'
                    ? 'bg-blue-600 text-white'
                    : 'bg-white text-gray-700'"
                @click="filter = 'all'"
            >
                All Alerts
            </button>

            <button
                class="px-4 py-1 rounded border"
                :class="filter === 'abnormal'
                    ? 'bg-red-600 text-white'
                    : 'bg-white text-gray-700'"
                @click="filter = 'abnormal'"
            >
                Abnormal Only
            </button>
        </div>

        <div class="bg-white p-4 rounded shadow h-80 overflow-y-scroll">
            <template x-if="filteredAlerts().length === 0">
                <p class="text-gray-500">No alerts</p>
            </template>

            <template x-for="alert in filteredAlerts()" :key="alert.id">
                <div class="flex justify-between items-center border-b py-2">
                    <div class="flex items-center gap-2">
                        <span>
                            Chicken #
                            <span class="font-semibold" x-text="alert.chicken_id"></span>
                        </span>

                        <!-- BADGE -->
                        <span
                            class="text-xs px-2 py-1 rounded"
                            :class="alert.behavior.includes('Abnormal')
                                ? 'bg-red-100 text-red-700'
                                : 'bg-green-100 text-green-700'"
                            x-text="alert.behavior"
                        ></span>
                    </div>

                    <button
                        x-show="alert.video_path"
                        class="text-blue-600 hover:underline"
                        @click="
                            videoSrc='/' + alert.video_path;
                            open=true;
                            $nextTick(() => $refs.player.play())
                        "
                    >
                        ▶ View
                    </button>
                </div>
            </template>
        </div>

        <div
            x-show="open"
            x-transition
            @keydown.escape.window="open=false; $refs.player.pause()"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70"
            style="display: none;"
        >
            <div
                class="absolute inset-0"
                @click="open=false; $refs.player.pause()"
            ></div>

            <div class="relative bg-white rounded-lg shadow-lg w-3/4 max-w-3xl p-4 z-10">
                <video
                    x-ref="player"
                    :src="videoSrc"
                    controls
                    class="w-full rounded"
                ></video>

                <button
                    class="mt-3 text-red-600 hover:underline"
                    @click="open=false; $refs.player.pause()"
                >
                    Close
                </button>
            </div>
        </div>

    </div>
</x-app-layout>
