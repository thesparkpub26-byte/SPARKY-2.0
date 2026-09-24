import { onBeforeUnmount, ref, watch } from 'vue';

// Search helpers shared by the staff dashboards, so every search bar behaves the same way.

// (...values) => true when the search box is empty or any value contains what was typed
export const makeMatcher = (queryRef) => (...values) => {
    const needle = String(queryRef.value ?? '').trim().toLowerCase();
    if (!needle) return true;

    return values.some((value) => String(value ?? '').toLowerCase().includes(needle));
};

// Press-work folders: keep a year if its name matches or any of its sheets do
export const searchAcademicYears = (years, matches) => (years || [])
    .map((yearGroup) => ({
        ...yearGroup,
        monitoring_sheets: (yearGroup.monitoring_sheets || []).filter((sheet) => matches(
            yearGroup.academic_year,
            sheet.title,
            sheet.publication_type,
        )),
    }))
    .filter((yearGroup) => matches(yearGroup.academic_year) || yearGroup.monitoring_sheets.length);

// The sentence for the "nothing found" state of a folder list
export const noPressWorksText = (searching) => (searching
    ? 'No press works match your search.'
    : "No press works available yet. New press works will appear here once they're created.");

// A search box that doesn't re-filter every table on each keystroke. Bind the box to `input`; everything
// that filters reads `query`, which follows `input` a moment after typing stops (and instantly when cleared).
export const useDebouncedSearch = (delay = 200) => {
    const input = ref('');
    const query = ref('');
    let timer;

    watch(input, (value) => {
        clearTimeout(timer);
        timer = setTimeout(() => { query.value = value; }, value ? delay : 0);
    });
    onBeforeUnmount(() => clearTimeout(timer));

    return { input, query };
};
