<template>
    <div class="card peak-card">
        <div class="peak-head">
            <h3 class="card-header" style="margin-bottom: 0;">Peak Viewing Time</h3>
            <button type="button" class="peak-toggle" :disabled="!total" @click="showGraph = !showGraph">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                {{ showGraph ? 'Hide Graph' : 'Show Graph' }}
            </button>
        </div>

        <p v-if="!total" class="peak-empty">No article views recorded for this period yet.</p>
        <template v-else>
            <p class="peak-summary">
                Most readers visit around <strong>{{ hourRange(peakHour) }}</strong>
                <span class="peak-sub">{{ views[peakHour] }} {{ views[peakHour] === 1 ? 'view' : 'views' }} at that hour, out of {{ total }} in this period</span>
            </p>

            <div v-if="showGraph" class="peak-chart">
                <div class="peak-plot">
                    <div class="peak-grid" aria-hidden="true">
                        <span>{{ max }}</span><span>{{ Math.round(max / 2) }}</span><span>0</span>
                    </div>
                    <div class="peak-bars" @mouseleave="hovered = null">
                        <div
                            v-for="(count, hour) in views"
                            :key="hour"
                            class="peak-col"
                            @mouseenter="hovered = hour"
                        >
                            <div
                                class="peak-bar"
                                :class="{ peak: hour === peakHour && count > 0, active: hovered === hour }"
                                :style="{ height: count ? Math.max(4, (count / max) * 100) + '%' : '2px' }"
                            ></div>
                        </div>
                    </div>
                </div>
                <div class="peak-axis">
                    <span v-for="hour in ticks" :key="hour" :style="{ left: (hour / 24) * 100 + '%' }">{{ hourLabel(hour) }}</span>
                </div>
                <p class="peak-readout">
                    <template v-if="hovered !== null">
                        <strong>{{ hourRange(hovered) }}</strong>: {{ views[hovered] }} {{ views[hovered] === 1 ? 'view' : 'views' }} &middot; {{ visitors[hovered] }} {{ visitors[hovered] === 1 ? 'visitor' : 'visitors' }}
                    </template>
                    <template v-else>Hover over a bar to see the hour. Times are in Philippine time.</template>
                </p>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// When during the day readers open articles: page views (and distinct visitors) for each hour, 12 AM - 11 PM.
const props = defineProps({
    hourly: { type: Object, default: () => ({ views: [], visitors: [] }) }
});

const showGraph = ref(false);
const hovered = ref(null);

const pad = (list) => Array.from({ length: 24 }, (_, i) => Number(list?.[i] || 0));
const views = computed(() => pad(props.hourly?.views));
const visitors = computed(() => pad(props.hourly?.visitors));

const total = computed(() => views.value.reduce((sum, n) => sum + n, 0));
const max = computed(() => Math.max(1, ...views.value));
const peakHour = computed(() => views.value.indexOf(Math.max(...views.value)));

const ticks = [0, 3, 6, 9, 12, 15, 18, 21];

const hourLabel = (h) => `${h % 12 === 0 ? 12 : h % 12} ${h < 12 ? 'AM' : 'PM'}`;
const hourRange = (h) => `${hourLabel(h)} – ${hourLabel((h + 1) % 24)}`;
</script>

<style scoped>
.peak-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
}

.peak-toggle {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 1.5px solid #dbeafe;
    border-radius: 999px;
    background: #eff6ff;
    color: #1d4ed8;
    font-family: inherit;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: background-color 0.2s, border-color 0.2s;
}

.peak-toggle:hover:not(:disabled) {
    background: #dbeafe;
    border-color: #93c5fd;
}

.peak-toggle:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.peak-empty {
    font-size: 13px;
    color: #94a3b8;
    font-weight: 600;
}

.peak-summary {
    font-size: 14px;
    color: #475569;
    line-height: 1.5;
}

.peak-summary strong {
    color: #0f172a;
    font-weight: 800;
}

.peak-sub {
    display: block;
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
    margin-top: 2px;
}

.peak-chart {
    margin-top: 18px;
}

.peak-plot {
    display: flex;
    gap: 8px;
    height: 170px;
}

.peak-grid {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    font-size: 10.5px;
    font-weight: 600;
    color: #94a3b8;
    text-align: right;
    min-width: 20px;
    line-height: 1;
}

.peak-bars {
    flex: 1;
    display: flex;
    align-items: flex-end;
    gap: 3px;
    border-bottom: 1.5px solid #e2e8f0;
    background: repeating-linear-gradient(to top, transparent 0, transparent calc(50% - 1px), #f1f5f9 calc(50% - 1px), #f1f5f9 50%);
}

.peak-col {
    flex: 1;
    height: 100%;
    display: flex;
    align-items: flex-end;
    cursor: default;
}

.peak-bar {
    width: 100%;
    border-radius: 4px 4px 0 0;
    background: #93c5fd;
    transition: background-color 0.15s, height 0.3s ease;
}

.peak-bar.peak {
    background: #1a73e8;
}

.peak-bar.active {
    background: #1d4ed8;
}

.peak-axis {
    position: relative;
    height: 18px;
    margin: 6px 0 0 28px;
}

.peak-axis span {
    position: absolute;
    top: 0;
    font-size: 10.5px;
    font-weight: 600;
    color: #94a3b8;
    transform: translateX(-2px);
    white-space: nowrap;
}

.peak-readout {
    margin-top: 10px;
    font-size: 12.5px;
    color: #64748b;
    font-weight: 500;
    min-height: 18px;
}

.peak-readout strong {
    color: #0f172a;
}
</style>
